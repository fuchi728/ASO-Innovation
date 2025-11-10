Vue.component('image-slider', {
    props: ['images'],
    data() {
        return {
            current: 0,
            start: 0
        };
    },
    template: `
        <div class="slider" 
            @touchstart="onTouchStart" 
            @touchend="onTouchEnd">
            <figure>
                <img :src="'item-image/' + images[current]" alt="商品画像"></image-slider>
            </figure>
        </div>
    `,
    methods: {
        next() {
            this.current = (this.current + 1) % this.images.length;
        },
        prev() {
            this.current = (this.current - 1 + this.images.length) % this.images.length;
        },
        go(index) {
            this.current = index;
        },
        onTouchStart(e) {
            this.start = e.changedTouches[0].clientX;
        },
        onTouchEnd(e) {
            const end = e.changedTouches[0].clientX;
            if (end - this.start > 50) {
                this.prev();
            } else if (this.start - end > 50) {
                this.next();
            }
        }
    }
});

new Vue({
    el: '#app',
})