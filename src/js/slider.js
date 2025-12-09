import Swiper  from "swiper";
import { Navigation} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import { FreeMode } from "swiper/modules";

document.addEventListener('DOMContentLoaded', function(){
    if (document.querySelector('.slider')){
        const opciones ={
            slidesPerView: 1,
            spaceBetween: 10,
            FreeMode: true,
            navigation:{
                nextEl:'.swiper-button-next',
                prevEl:'.swiper-button-prev'
            },
            breakpoints:{
                768:{
                    slidesPerView: 6
                },
                1024:{
                    slidesPerView: 6
                },
                1200:{
                    slidesPerView: 8
                }               
            }
        }
        Swiper.use([Navigation])
        new Swiper('.slider',opciones)
    }
})