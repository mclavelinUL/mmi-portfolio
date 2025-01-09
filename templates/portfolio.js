let selects = document.querySelectorAll('.portfolio-pedagogique-archive .filtres select');
if(selects){
    selects.forEach(select => {
        select.addEventListener('change', function(e){
            console.log('change');
            document.querySelector('.portfolio-pedagogique-archive #filtres').submit();
        });
    });
}
let sliders = document.querySelectorAll('.swiper')
console.log(sliders);
if(sliders){
    
    sliders.forEach(slider => {
        let swiper = new Swiper(slider, {
            slidesPerView: 3,
            spaceBetween: 10,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
}
