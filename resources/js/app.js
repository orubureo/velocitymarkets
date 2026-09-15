window.cryptoIconFallback = function (img, ticker) {
    const wrap = img.parentElement;
    img.remove();
    wrap.classList.add('items-center', 'justify-center', 'font-bold', 'text-zinc-500', 'dark:text-zinc-300');
    wrap.style.fontSize = Math.max(8, Math.round(wrap.offsetWidth * 0.32)) + 'px';
    wrap.textContent = (ticker || '').slice(0, 3).toUpperCase();
};

window.avatarImgFallback = function (img, initials) {
    const wrap = img.parentElement;
    img.remove();
    wrap.classList.add('items-center', 'justify-center', 'font-semibold', 'text-zinc-600', 'dark:text-zinc-300', 'bg-zinc-200', 'dark:bg-zinc-700');
    wrap.textContent = initials || '';
};
