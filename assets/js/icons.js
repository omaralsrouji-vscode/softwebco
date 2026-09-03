(function () {
  'use strict';

  const P = {
    circle: '<circle cx="12" cy="12" r="9"/>',
    check: '<path d="M5 12.5l4.2 4.2L19 7"/>',
    checkCircle: '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16.5 9"/>',
    arrowLeft: '<path d="M19 12H5m6-6-6 6 6 6"/>',
    arrowRight: '<path d="M5 12h14m-6-6 6 6-6 6"/>',
    arrowUp: '<path d="M12 19V5m-6 6 6-6 6 6"/>',
    external: '<path d="M14 5h5v5M19 5l-8 8"/><path d="M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5"/>',
    play: '<path d="M9 7l8 5-8 5z"/>',
    phone: '<path d="M7.5 4.5 10 8l-2 2c1.2 2.7 3.3 4.8 6 6l2-2 3.5 2.5c.5.4.6 1 .3 1.6-.7 1.3-2 2-3.5 1.8C10 19 5 14 4.1 7.7 3.9 6.2 4.6 4.9 5.9 4.2c.6-.3 1.2-.2 1.6.3z"/>',
    mail: '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
    send: '<path d="M21 3 10 14"/><path d="m21 3-7 18-4-7-7-4z"/>',
    user: '<circle cx="12" cy="8" r="3.5"/><path d="M5 20c.8-4 3.1-6 7-6s6.2 2 7 6"/>',
    users: '<path d="M8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm8-1a2.5 2.5 0 1 0 0-5"/><path d="M2.5 20c.7-3.7 2.5-5.5 5.5-5.5s4.8 1.8 5.5 5.5M14 14.5c3 0 5 1.7 5.6 5"/>',
    lock: '<rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
    shield: '<path d="M12 3 5 6v5c0 4.6 2.8 7.9 7 10 4.2-2.1 7-5.4 7-10V6z"/><path d="m9 12 2 2 4-4"/>',
    eye: '<path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/><circle cx="12" cy="12" r="2.5"/>',
    eyeOff: '<path d="M3 3l18 18M10.4 6.2A9.7 9.7 0 0 1 12 6c6 0 9.5 6 9.5 6a16 16 0 0 1-3 3.7M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 6 9.5 6a9 9 0 0 0 3-.5"/>',
    search: '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/>',
    list: '<path d="M8 6h12M8 12h12M8 18h12"/><circle cx="4" cy="6" r=".7" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r=".7" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r=".7" fill="currentColor" stroke="none"/>',
    grid: '<rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/>',
    plus: '<path d="M12 5v14M5 12h14"/>',
    x: '<path d="m6 6 12 12M18 6 6 18"/>',
    pencil: '<path d="m4 20 4.2-1 10.6-10.6-3.2-3.2L5 15.8zM14.8 6l3.2 3.2"/>',
    trash: '<path d="M4 7h16M9 7V4h6v3M7 7l1 13h8l1-13M10 11v5M14 11v5"/>',
    tag: '<path d="M3 5v6l8 8 8-8-8-8H5a2 2 0 0 0-2 2z"/><circle cx="7.5" cy="7.5" r="1"/>',
    journal: '<path d="M5 4h12a2 2 0 0 1 2 2v14H7a2 2 0 0 1-2-2z"/><path d="M8 4v16M11 8h5M11 12h5"/>',
    globe: '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.3 2.5 3.5 5.5 3.5 9S14.3 18.5 12 21M12 3C9.7 5.5 8.5 8.5 8.5 12S9.7 18.5 12 21"/>',
    info: '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7h.01"/>',
    warning: '<circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 17h.01"/>',
    star: '<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9z"/>',
    heart: '<path d="M20.8 5.8a5.2 5.2 0 0 0-7.4 0L12 7.2l-1.4-1.4a5.2 5.2 0 0 0-7.4 7.4L12 22l8.8-8.8a5.2 5.2 0 0 0 0-7.4z"/>',
    lightbulb: '<path d="M9 18h6M10 21h4M8.5 15.5A6 6 0 1 1 15.5 15.5c-.8.6-1.2 1.2-1.3 2.5h-4.4c-.1-1.3-.5-1.9-1.3-2.5z"/>',
    handshake: '<path d="m3 12 4-4 4 2 2-2 8 7-3 3-3-1-2 2-3-1-2 1-5-5z"/><path d="m8 10 4 4"/>',
    code: '<path d="m8 8-4 4 4 4M16 8l4 4-4 4M14 5l-4 14"/>',
    headset: '<path d="M4 14v-2a8 8 0 0 1 16 0v2M4 14h3v5H5a1 1 0 0 1-1-1zM20 14h-3v5h2a1 1 0 0 0 1-1zM17 19c0 1.1-.9 2-2 2h-3"/>',
    calendar: '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/>',
    clock: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    building: '<path d="M5 21V4h10v17M15 9h4v12M8 8h2M8 12h2M8 16h2M18 13h.01M18 17h.01"/>',
    briefcase: '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V4h6v3M3 12h18M10 12v2h4v-2"/>',
    database: '<ellipse cx="12" cy="5" rx="7" ry="3"/><path d="M5 5v6c0 1.7 3.1 3 7 3s7-1.3 7-3V5M5 11v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/>',
    cart: '<path d="M3 4h2l2 11h10l2-7H7M9 20h.01M17 20h.01"/>',
    box: '<path d="m4 7 8-4 8 4-8 4zM4 7v10l8 4 8-4V7M12 11v10"/>',
    truck: '<path d="M3 6h11v10H3zM14 10h4l3 3v3h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>',
    chart: '<path d="M4 20V10M10 20V5M16 20v-8M22 20H2"/>',
    card: '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h4"/>',
    receipt: '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6M9 16h4"/>',
    car: '<path d="m5 11 2-5h10l2 5M4 11h16v7H4z"/><circle cx="7" cy="18" r="1.5"/><circle cx="17" cy="18" r="1.5"/>',
    wrench: '<path d="M14 6a5 5 0 0 0-6.6 6.6L3 17l4 4 4.4-4.4A5 5 0 0 0 18 10l-3 3-3-3z"/>',
    bell: '<path d="M6 9a6 6 0 0 1 12 0c0 7 3 7 3 8H3c0-1 3-1 3-8M10 21h4"/>',
    image: '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8" cy="9" r="2"/><path d="m3 17 5-5 4 4 3-3 6 5"/>',
    file: '<path d="M6 3h8l4 4v14H6zM14 3v5h5M9 12h6M9 16h6"/>',
    copy: '<rect x="8" y="8" width="11" height="11" rx="2"/><path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/>',
    gear: '<circle cx="12" cy="12" r="3"/><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M18.4 5.6 17 7M7 17l-1.4 1.4"/>',
    location: '<path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11z"/><circle cx="12" cy="10" r="2"/>',
    terminal: '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="m7 9 3 3-3 3M12 16h5"/>',
    bolt: '<path d="M13 2 5 13h6l-1 9 9-13h-6z"/>',
    rocket: '<path d="M14 4c3-2 6-1 6-1s1 3-1 6l-5 5-4-4zM10 10l-4 1-3 3 6 1M14 14l-1 4-3 3-1-6"/><path d="M7 17c-2 0-3 1-4 4 3-1 4-2 4-4z"/>',
    mobile: '<rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M10 5h4M11.5 18.5h1"/>',
    desktop: '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>',
    dollar: '<circle cx="12" cy="12" r="9"/><path d="M15.5 8.5c-.8-1-2-1.5-3.5-1.5-2 0-3.5 1-3.5 2.5 0 4 7 1.5 7 5.5 0 1.5-1.5 2.5-3.5 2.5-1.6 0-2.9-.5-3.8-1.6M12 5v14"/>',
    quote: '<path d="M5 9h5v5H7c0 2-1 3-3 4M14 9h5v5h-3c0 2-1 3-3 4"/>',
    flag: '<path d="M5 21V4M5 5h10l-1 4 3 3H5"/>',
    fingerprint: '<path d="M8 12a4 4 0 0 1 8 0c0 4-1 7-3 9M5 12a7 7 0 0 1 14 0c0 3-.4 5.5-1.2 7.5M11 12a1 1 0 0 1 2 0c0 3-.4 5.3-1.3 7.5M4 17c.6-1.6 1-3.3 1-5"/>',
    magic: '<path d="m4 20 10-10M13 4l.7 1.8L16 6.5l-2.3.7L13 9l-.7-1.8L10 6.5l2.3-.7zM18 12l.5 1.3 1.5.5-1.5.5L18 16l-.5-1.7-1.5-.5 1.5-.5z"/><path d="m5 15 4 4"/>',
    cube: '<path d="m12 3 8 4.5v9L12 21l-8-4.5v-9zM4 7.5l8 4.5 8-4.5M12 12v9"/>',
    restaurant: '<path d="M6 3v7M3 3v4c0 2 1 3 3 3s3-1 3-3V3M6 10v11M15 3v18M15 3c4 1 5 4 5 7h-5"/>',
    home: '<path d="m3 11 9-8 9 8M5 10v10h14V10M9 20v-6h6v6"/>',
    layers: '<path d="m12 3 9 5-9 5-9-5zM3 12l9 5 9-5M3 16l9 5 9-5"/>',
    facebook: '<path fill="currentColor" stroke="none" d="M13.5 22v-8h2.8l.4-3h-3.2V9.1c0-.9.3-1.6 1.6-1.6H17V4.8c-.4-.1-1.4-.2-2.5-.2-2.5 0-4.2 1.5-4.2 4.3V11H7.5v3h2.8v8z"/>',
    instagram: '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.8" r=".8" fill="currentColor" stroke="none"/>',
    tiktok: '<path d="M14 4v10.5a4.5 4.5 0 1 1-3.2-4.3"/><path d="M14 4c1 2.2 2.7 3.5 5 3.8"/>'
  };

  function resolve(name) {
    name = String(name || '').toLowerCase().replace(/^(fa|bi)-/, '');
    const exact = {
      'facebook':'facebook','instagram':'instagram','tiktok':'tiktok',
      'phone-alt':'phone','envelope':'mail','paper-plane':'send','send':'send',
      'arrow-left':'arrowLeft','chevron-left':'arrowLeft','arrow-right':'arrowRight','chevron-right':'arrowRight','arrow-up':'arrowUp',
      'arrow-up-right-from-square':'external','box-arrow-up-right':'external','box-arrow-right':'arrowRight',
      'play':'play','check':'check','check2':'check','circle-check':'checkCircle','check-circle':'checkCircle',
      'x-lg':'x','plus-lg':'plus','search':'search','list':'list','grid-1x2-fill':'grid',
      'person':'user','user':'user','person-plus':'users','users':'users','people':'users','people-fill':'users','people-group':'users','user-tie':'user','user-gear':'user','address-book':'users',
      'lock':'lock','lock-fill':'lock','shield-lock':'shield','shield-check':'shield','shield-alt':'shield','shield-halved':'shield',
      'eye':'eye','eye-slash':'eyeOff','info-circle':'info','info-circle-fill':'info','exclamation-circle':'warning',
      'pencil':'pencil','pencil-square':'pencil','trash3':'trash','tag':'tag','tags':'tag','tags-fill':'tag',
      'journal':'journal','journal-richtext':'journal','globe2':'globe','star':'star','heart':'heart','lightbulb':'lightbulb','handshake':'handshake','code':'code','headset':'headset',
      'calendar':'calendar','calendar-check':'calendar','calendar-day':'calendar','calendar-days':'calendar','calendar-plus':'calendar','clock':'clock','clock-rotate-left':'clock','hourglass-half':'clock',
      'building':'building','building-columns':'building','briefcase':'briefcase','database':'database','cart-shopping':'cart','box-open':'box','boxes-stacked':'box','truck-field':'truck','cash-register':'receipt','credit-card':'card','receipt':'receipt','car-side':'car','screwdriver-wrench':'wrench','bell':'bell',
      'chart-line':'chart','chart-column':'chart','bars-progress':'chart','gauge-high':'chart','scale-balanced':'chart',
      'image':'image','file-lines':'file','file-arrow-up':'file','file-signature':'file','clipboard-list':'list','table-list':'list','list-check':'list','copy':'copy',
      'gears':'gear','location-dot':'location','terminal':'terminal','bolt':'bolt','rocket':'rocket','mobile-alt':'mobile','mobile-screen':'mobile','mobile-screen-button':'mobile','display':'desktop','desktop':'desktop',
      'dollar-sign':'dollar','quote-left':'quote','flag':'flag','fingerprint':'fingerprint','wand-magic-sparkles':'magic','cube':'cube','utensils':'restaurant','house-medical':'home','layer-group':'layers','diagram-project':'layers',
      'face-smile':'circle','circle':'circle','bootstrap':'code','php':'code','js':'code'
    };
    if (exact[name]) return exact[name];
    if (name.includes('arrow-left')) return 'arrowLeft';
    if (name.includes('arrow-right')) return 'arrowRight';
    if (name.includes('arrow-up')) return 'arrowUp';
    if (name.includes('calendar')) return 'calendar';
    if (name.includes('user') || name.includes('person') || name.includes('people')) return 'users';
    if (name.includes('shield') || name.includes('lock')) return 'shield';
    if (name.includes('chart') || name.includes('progress')) return 'chart';
    if (name.includes('file') || name.includes('journal')) return 'file';
    if (name.includes('mobile')) return 'mobile';
    return 'circle';
  }

  function iconName(el) {
    const classes = Array.from(el.classList || []);
    return classes.find(c => /^(fa|bi)-/.test(c)) || '';
  }

  function renderSvg(name) {
    const key = resolve(name);
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '1.8');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');
    svg.setAttribute('aria-hidden', 'true');
    svg.setAttribute('focusable', 'false');
    svg.setAttribute('data-swc-icon', name);
    svg.classList.add('swc-svg-icon');
    svg.innerHTML = P[key] || P.circle;
    return svg;
  }

  function hydrate(el, forcedName) {
    if (!el) return null;
    const name = forcedName || iconName(el);
    if (!name) return null;
    el.classList.add('swc-icon-host');
    el.setAttribute('aria-hidden', 'true');
    el.setAttribute('data-swc-icon-host', name);
    el.innerHTML = '';
    el.appendChild(renderSvg(name));
    return el;
  }

  function replace(root) {
    const scope = root && root.querySelectorAll ? root : document;
    scope.querySelectorAll('i.fas, i.far, i.fab, i.fal, i.fad, i.bi').forEach(el => hydrate(el));
  }

  function setIcon(el, name) {
    if (!el) return null;
    const host = el.matches && el.matches('i') ? el : (el.closest ? el.closest('i.swc-icon-host') : null);
    if (host) return hydrate(host, name);
    if (el.tagName && el.tagName.toLowerCase() === 'svg') {
      const key = resolve(name);
      el.setAttribute('data-swc-icon', name);
      el.innerHTML = P[key] || P.circle;
      return el;
    }
    return null;
  }

  window.SoftwebcoIcons = { replace, setIcon, resolve };
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', () => replace(document), { once: true });
  else replace(document);
})();
