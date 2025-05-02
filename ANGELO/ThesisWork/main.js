const swiperHome = new Swiper('.home__swiper', {
	speed: 1200,
	effect: 'fade',
  pagination: {
    el: '.swiper-pagination',
	clickable: true,
	renderBullet: (index, className) => {
          return '<span class="' + className + '">' + String(index + 1).padStart(2, '0') + "</span>";
        },
  },
});

gsap.from('.hp1', {y: -1000, duration: 2})
gsap.from('.hp2', {y: 1000, duration: 2})
gsap.from('.home__image', {x: 1000, duration: 2})
gsap.from('.home__titles', {y: 100, opacity: 0, delay: 2})
gsap.from('.home__title', {y: 100, opacity: 0, delay: 2.1})