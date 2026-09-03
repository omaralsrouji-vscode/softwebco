<?php
require_once '../config.php';
swc_require_admin();

require_once '../WebDesign.php';
$design = new WebDesign();

function swc_user_store_profile(string $field, array &$errors): ?string
{
    if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) {
        return null;
    }

    $file = $_FILES[$field];
    $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($error !== UPLOAD_ERR_OK) {
        $errors[] = 'Could not upload the profile image.';
        return null;
    }

    if ((int)($file['size'] ?? 0) > 2 * 1024 * 1024) {
        $errors[] = 'Profile image must be smaller than 2MB.';
        return null;
    }

    $tmp = (string)($file['tmp_name'] ?? '');
    $mime = '';
    if ($tmp !== '' && is_file($tmp)) {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime = (string)finfo_file($finfo, $tmp);
                finfo_close($finfo);
            }
        } elseif (function_exists('mime_content_type')) {
            $mime = (string)mime_content_type($tmp);
        }
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) {
        $errors[] = 'Profile image must be JPG, PNG or WebP.';
        return null;
    }

    $dir = __DIR__ . '/../storage/profile-images/';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        $errors[] = 'Could not prepare the profile image folder.';
        return null;
    }

    $name = 'user_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($tmp, $dir . $name)) {
        $errors[] = 'Could not save the profile image.';
        return null;
    }

    return $name;
}

function swc_user_delete_profile_file(?string $filename): void
{
    $filename = basename(trim((string)$filename));
    if ($filename === '' || $filename === 'default-avatar.png') {
        return;
    }
    $path = __DIR__ . '/../storage/profile-images/' . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

$view = (string)($_GET['view'] ?? 'list');
if (!in_array($view, ['list', 'new', 'edit'], true)) {
    $view = 'list';
}
$editId = max(0, (int)($_GET['id'] ?? 0));
$message = '';
$editUser = null;

if ($view === 'edit') {
    if ($editId <= 0) {
        header('Location: users.php');
        exit();
    }
    try {
        $stmt = $conn->prepare('SELECT id,username,email,display_name,profile_image,bio,account_status,is_locked,created_at,updated_at FROM users WHERE id=? LIMIT 1');
        $stmt->bind_param('i', $editId);
        $stmt->execute();
        $editUser = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } catch (Throwable $e) {
        error_log('Load edit user: ' . $e->getMessage());
    }
    if (!$editUser) {
        header('Location: users.php?missing=1');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($view, ['new', 'edit'], true)) {
    $username = trim((string)($_POST['username'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $display = trim((string)($_POST['display_name'] ?? ''));
    $bio = trim((string)($_POST['bio'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    $errors = [];

    if (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }
    if (!preg_match('/^[A-Za-z0-9._-]+$/', $username)) {
        $errors[] = 'Username may contain only letters, numbers, dots, dashes and underscores.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($view === 'new') {
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }
    } elseif ($password !== '') {
        if (strlen($password) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }
    }

    try {
        if ($view === 'edit') {
            $check = $conn->prepare('SELECT id FROM users WHERE (username=? OR email=?) AND id<>? LIMIT 1');
            $check->bind_param('ssi', $username, $email, $editId);
        } else {
            $check = $conn->prepare('SELECT id FROM users WHERE username=? OR email=? LIMIT 1');
            $check->bind_param('ss', $username, $email);
        }
        $check->execute();
        if ($check->get_result()->num_rows) {
            $errors[] = 'Username or email already exists.';
        }
        $check->close();
    } catch (Throwable $e) {
        $errors[] = 'Could not validate the account.';
        error_log('Validate user: ' . $e->getMessage());
    }

    $newProfile = null;
    if (!$errors) {
        $newProfile = swc_user_store_profile('profile_image', $errors);
    }

    if (!$errors) {
        $display = $display !== '' ? $display : $username;

        try {
            if ($view === 'new') {
                $profile = $newProfile ?: 'default-avatar.png';
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users(username,email,display_name,profile_image,bio,password,account_status,is_locked) VALUES(?,?,?,?,?,?,'active',0)");
                $stmt->bind_param('ssssss', $username, $email, $display, $profile, $bio, $hash);
                $stmt->execute();
                $stmt->close();

                header('Location: users.php?created=1');
                exit();
            }

            $oldProfile = (string)($editUser['profile_image'] ?? 'default-avatar.png');
            $profile = $newProfile ?: $oldProfile;
            $currentId = (int)($_SESSION['user_id'] ?? 0);
            $isCurrent = $editId === $currentId;
            $allowedStatuses = ['active', 'suspended', 'deactivated'];
            $status = (string)($_POST['account_status'] ?? ($editUser['account_status'] ?? 'active'));
            if (!in_array($status, $allowedStatuses, true)) {
                $status = 'active';
            }
            // Never let an administrator accidentally lock themselves out while editing.
            if ($isCurrent) {
                $status = 'active';
            }

            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('UPDATE users SET username=?,email=?,display_name=?,profile_image=?,bio=?,password=?,account_status=? WHERE id=?');
                $stmt->bind_param('sssssssi', $username, $email, $display, $profile, $bio, $hash, $status, $editId);
            } else {
                $stmt = $conn->prepare('UPDATE users SET username=?,email=?,display_name=?,profile_image=?,bio=?,account_status=? WHERE id=?');
                $stmt->bind_param('ssssssi', $username, $email, $display, $profile, $bio, $status, $editId);
            }
            $stmt->execute();
            $stmt->close();

            if ($newProfile && $oldProfile !== $newProfile) {
                swc_user_delete_profile_file($oldProfile);
            }

            if ($isCurrent) {
                $_SESSION['username'] = $username;
            }

            header('Location: users.php?updated=1');
            exit();
        } catch (Throwable $e) {
            if ($newProfile) {
                swc_user_delete_profile_file($newProfile);
            }
            $errors[] = $view === 'new' ? 'Could not create the user.' : 'Could not update the user.';
            error_log('Save user: ' . $e->getMessage());
        }
    }

    if ($errors) {
        $message = implode(' ', $errors);
    }

    // Keep the submitted values visible after validation errors.
    if ($view === 'edit' && is_array($editUser)) {
        $editUser['username'] = $username;
        $editUser['email'] = $email;
        $editUser['display_name'] = $display;
        $editUser['bio'] = $bio;
        if (isset($_POST['account_status'])) {
            $editUser['account_status'] = (string)$_POST['account_status'];
        }
    }
}

$current_user = swc_current_admin_user($conn);

$search = trim((string)($_GET['search'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;
$users = [];
$total = 0;

if ($view === 'list') {
    try {
        if ($search !== '') {
            $like = '%' . $search . '%';
            $stmt = $conn->prepare('SELECT COUNT(*) c FROM users WHERE username LIKE ? OR email LIKE ? OR display_name LIKE ?');
            $stmt->bind_param('sss', $like, $like, $like);
            $stmt->execute();
            $total = (int)$stmt->get_result()->fetch_assoc()['c'];
            $stmt->close();

            $stmt = $conn->prepare('SELECT id,username,email,display_name,profile_image,bio,account_status,is_locked,created_at,updated_at FROM users WHERE username LIKE ? OR email LIKE ? OR display_name LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?');
            $stmt->bind_param('sssii', $like, $like, $like, $limit, $offset);
        } else {
            $result = $conn->query('SELECT COUNT(*) c FROM users');
            if ($result) {
                $total = (int)$result->fetch_assoc()['c'];
            }
            $stmt = $conn->prepare('SELECT id,username,email,display_name,profile_image,bio,account_status,is_locked,created_at,updated_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?');
            $stmt->bind_param('ii', $limit, $offset);
        }
        $stmt->execute();
        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } catch (Throwable $e) {
        error_log('Users list: ' . $e->getMessage());
    }
}
$pages = max(1, (int)ceil($total / $limit));

$isEdit = $view === 'edit';
$formUser = $isEdit ? $editUser : [
    'id' => 0,
    'username' => (string)($_POST['username'] ?? ''),
    'email' => (string)($_POST['email'] ?? ''),
    'display_name' => (string)($_POST['display_name'] ?? ''),
    'bio' => (string)($_POST['bio'] ?? ''),
    'profile_image' => 'default-avatar.png',
    'account_status' => 'active',
];
?>
<!doctype html>
<html lang="en">
<head><?php $design->GenerateHeadTag2(); ?></head>
<body>
<?php $design->ShowNavbar2('users', $current_user); ?>

<?php if ($view === 'new' || $view === 'edit'): ?>
    <?php if ($message): ?>
        <div class="swc-alert swc-alert-error"><i class="bi bi-exclamation-circle"></i><span><?php echo htmlspecialchars($message); ?></span></div>
    <?php endif; ?>

    <div style="margin-bottom:16px">
        <a class="swc-btn swc-btn-ghost swc-btn-sm" href="users.php"><i class="bi bi-arrow-left"></i> Back to users</a>
    </div>

    <form method="post" enctype="multipart/form-data" class="swc-form-layout" autocomplete="off">
        <section class="swc-form-card">
            <div class="swc-form-head">
                <h2><?php echo $isEdit ? 'Edit user' : 'Create user'; ?></h2>
                <p><?php echo $isEdit ? 'Update account details, password or profile image.' : 'Add another administrator to the Softwebco workspace.'; ?></p>
            </div>

            <div class="swc-form-body">
                <div class="swc-field-grid">
                    <label class="swc-field">
                        <span class="swc-label">Username <b class="swc-required">*</b></span>
                        <input class="swc-input" name="username" value="<?php echo htmlspecialchars((string)$formUser['username']); ?>" required minlength="3" autocomplete="username">
                    </label>

                    <label class="swc-field">
                        <span class="swc-label">Display name</span>
                        <input class="swc-input" name="display_name" value="<?php echo htmlspecialchars((string)$formUser['display_name']); ?>">
                    </label>

                    <label class="swc-field swc-field-full">
                        <span class="swc-label">Email <b class="swc-required">*</b></span>
                        <input class="swc-input" type="email" name="email" value="<?php echo htmlspecialchars((string)$formUser['email']); ?>" required autocomplete="email">
                    </label>

                    <?php if ($isEdit): ?>
                        <label class="swc-field">
                            <span class="swc-label">New password</span>
                            <input class="swc-input" type="password" name="password" minlength="8" autocomplete="new-password">
                            <span class="swc-hint">Leave blank to keep the current password.</span>
                        </label>
                        <label class="swc-field">
                            <span class="swc-label">Confirm new password</span>
                            <input class="swc-input" type="password" name="confirm_password" minlength="8" autocomplete="new-password">
                        </label>
                    <?php else: ?>
                        <label class="swc-field">
                            <span class="swc-label">Password <b class="swc-required">*</b></span>
                            <input class="swc-input" type="password" name="password" required minlength="8" autocomplete="new-password">
                            <span class="swc-hint">At least 8 characters.</span>
                        </label>
                        <label class="swc-field">
                            <span class="swc-label">Confirm password <b class="swc-required">*</b></span>
                            <input class="swc-input" type="password" name="confirm_password" required minlength="8" autocomplete="new-password">
                        </label>
                    <?php endif; ?>

                    <?php if ($isEdit && (int)$formUser['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
                        <label class="swc-field">
                            <span class="swc-label">Account status</span>
                            <select class="swc-select" name="account_status" style="width:100%">
                                <?php foreach (['active' => 'Active', 'suspended' => 'Suspended', 'deactivated' => 'Deactivated'] as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" <?php echo (string)$formUser['account_status'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    <?php endif; ?>

                    <label class="swc-field swc-field-full">
                        <span class="swc-label">Bio</span>
                        <textarea class="swc-textarea" name="bio" placeholder="Optional note"><?php echo htmlspecialchars((string)$formUser['bio']); ?></textarea>
                    </label>

                    <?php if ($isEdit): ?>
                        <div class="swc-field swc-field-full">
                            <span class="swc-label">Current profile image</span>
                            <div class="swc-user-edit-photo">
                                <img src="<?php echo htmlspecialchars(swc_admin_profile_url((string)$formUser['profile_image'])); ?>" alt="">
                                <span>Upload a new image below only if you want to replace it.</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <label class="swc-field swc-field-full">
                        <span class="swc-label"><?php echo $isEdit ? 'Replace profile image' : 'Profile image'; ?></span>
                        <div class="swc-file">
                            <input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp">
                            <div class="swc-hint" style="margin-top:7px">JPG, PNG or WebP · maximum 2MB.</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="swc-form-actions">
                <a class="swc-btn swc-btn-ghost" href="users.php">Cancel</a>
                <button class="swc-btn swc-btn-primary" type="submit">
                    <i class="bi <?php echo $isEdit ? 'bi-check2' : 'bi-person-plus'; ?>"></i>
                    <?php echo $isEdit ? 'Save user' : 'Create user'; ?>
                </button>
            </div>
        </section>

        <aside class="swc-side-note">
            <div class="swc-side-note-icon"><i class="bi bi-shield-check"></i></div>
            <h3><?php echo $isEdit ? 'Account settings' : 'Admin access'; ?></h3>
            <p><?php echo $isEdit ? 'Changes take effect immediately after saving.' : 'Users created here can manage Dashboard, Users, Blogs and Categories.'; ?></p>
            <ul>
                <li><i class="bi bi-check2"></i><span>Username and email stay unique.</span></li>
                <li><i class="bi bi-check2"></i><span>Passwords are securely hashed.</span></li>
                <li><i class="bi bi-check2"></i><span>Your current account cannot delete itself.</span></li>
            </ul>
        </aside>
    </form>

<?php else: ?>
    <?php if (isset($_GET['created'])): ?>
        <div class="swc-alert swc-alert-success"><i class="bi bi-check-circle"></i><span>User created successfully.</span></div>
    <?php elseif (isset($_GET['updated'])): ?>
        <div class="swc-alert swc-alert-success"><i class="bi bi-check-circle"></i><span>User updated successfully.</span></div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="swc-alert swc-alert-success"><i class="bi bi-check-circle"></i><span>User deleted successfully.</span></div>
    <?php elseif (isset($_GET['delete_error'])): ?>
        <div class="swc-alert swc-alert-error"><i class="bi bi-exclamation-circle"></i><span><?php echo htmlspecialchars((string)$_GET['delete_error']); ?></span></div>
    <?php endif; ?>

    <section class="swc-panel">
        <div class="swc-panel-head">
            <div class="swc-panel-title">
                <h2>All users</h2>
                <p><?php echo $total; ?> account<?php echo $total === 1 ? '' : 's'; ?> in your workspace.</p>
            </div>
            <div class="swc-actions">
                <form class="swc-searchbar" method="get">
                    <div class="swc-searchbox"><i class="bi bi-search"></i><input name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search users"></div>
                    <?php if ($search !== ''): ?><a class="swc-btn swc-btn-ghost swc-btn-sm" href="users.php">Clear</a><?php endif; ?>
                </form>
                <a class="swc-btn swc-btn-primary" href="users.php?view=new"><i class="bi bi-person-plus"></i> Add user</a>
            </div>
        </div>

        <?php if (!$users): ?>
            <div class="swc-empty">
                <div class="swc-empty-icon"><i class="bi bi-people"></i></div>
                <h3>No users found</h3>
                <p><?php echo $search !== '' ? 'Try another search.' : 'Create another admin account when you need one.'; ?></p>
            </div>
        <?php else: ?>
            <div class="swc-table-wrap">
                <table class="swc-table">
                    <thead><tr><th>User</th><th>Status</th><th>Created</th><th>Last update</th><th style="text-align:right">Actions</th></tr></thead>
                    <tbody>
                    <?php foreach ($users as $u):
                        $nm = trim((string)$u['display_name']) ?: $u['username'];
                        $isCurrentRow = (int)$u['id'] === (int)($_SESSION['user_id'] ?? 0);
                    ?>
                        <tr>
                            <td>
                                <div class="swc-person-cell">
                                    <span class="swc-person-photo"><img src="<?php echo htmlspecialchars(swc_admin_profile_url($u['profile_image'])); ?>" alt=""></span>
                                    <span class="swc-person-info"><strong><?php echo htmlspecialchars($nm); ?></strong><small><?php echo htmlspecialchars($u['email']); ?> · @<?php echo htmlspecialchars($u['username']); ?></small></span>
                                </div>
                            </td>
                            <td><span class="swc-status <?php echo htmlspecialchars($u['account_status']); ?>"><?php echo $u['is_locked'] ? 'locked' : htmlspecialchars($u['account_status']); ?></span></td>
                            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($u['created_at']))); ?></td>
                            <td><?php echo htmlspecialchars(date('M j, Y', strtotime($u['updated_at']))); ?></td>
                            <td>
                                <div class="swc-row-actions">
                                    <a class="swc-btn swc-btn-ghost swc-btn-sm" href="users.php?view=edit&id=<?php echo (int)$u['id']; ?>"><i class="bi bi-pencil"></i> Edit</a>
                                    <?php if (!$isCurrentRow): ?>
                                        <form method="post" action="delete_user.php" class="swc-danger-form" onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                            <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                                            <button class="swc-btn swc-btn-danger swc-btn-sm" type="submit"><i class="bi bi-trash3"></i> Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="swc-category">Current user</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="swc-mobile-cards">
                <?php foreach ($users as $u):
                    $nm = trim((string)$u['display_name']) ?: $u['username'];
                    $isCurrentRow = (int)$u['id'] === (int)($_SESSION['user_id'] ?? 0);
                ?>
                    <article class="swc-mobile-card">
                        <div class="swc-mobile-card-head">
                            <div class="swc-mobile-card-meta">
                                <span class="swc-person-photo"><img src="<?php echo htmlspecialchars(swc_admin_profile_url($u['profile_image'])); ?>" alt=""></span>
                                <div><h3><?php echo htmlspecialchars($nm); ?></h3><p><?php echo htmlspecialchars($u['email']); ?></p></div>
                            </div>
                            <span class="swc-status <?php echo htmlspecialchars($u['account_status']); ?>"><?php echo $u['is_locked'] ? 'locked' : htmlspecialchars($u['account_status']); ?></span>
                        </div>
                        <div class="swc-mobile-card-actions">
                            <a class="swc-btn swc-btn-ghost swc-btn-sm" href="users.php?view=edit&id=<?php echo (int)$u['id']; ?>"><i class="bi bi-pencil"></i> Edit</a>
                            <?php if (!$isCurrentRow): ?>
                                <form method="post" action="delete_user.php" class="swc-danger-form" onsubmit="return confirm('Delete this user?')">
                                    <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                                    <button class="swc-btn swc-btn-danger swc-btn-sm" type="submit"><i class="bi bi-trash3"></i> Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="swc-category">Current user</span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($pages > 1): ?>
                <div class="swc-pagination">
                    <span class="swc-pagination-info">Page <?php echo $page; ?> of <?php echo $pages; ?></span>
                    <div class="swc-pagination-links">
                        <a class="swc-page-link <?php echo $page <= 1 ? 'is-disabled' : ''; ?>" href="?page=<?php echo max(1, $page - 1); ?>&search=<?php echo urlencode($search); ?>"><i class="bi bi-chevron-left"></i></a>
                        <?php for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++): ?>
                            <a class="swc-page-link <?php echo $i === $page ? 'is-active' : ''; ?>" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <a class="swc-page-link <?php echo $page >= $pages ? 'is-disabled' : ''; ?>" href="?page=<?php echo min($pages, $page + 1); ?>&search=<?php echo urlencode($search); ?>"><i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php $design->CloseAdminLayout(); ?>
</body>
</html>
