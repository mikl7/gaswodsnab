let iconCompair = document.querySelectorAll('.compair_product');
let imgCompairActiv = '/catalog/view/theme/gaswodsnab/image/icons/compair_product_active.png';
let imgCompairPassive = 'http://gaswodsnab/catalog/view/theme/gaswodsnab/image/icons/compair_product.png';


let iconBookmark = document.querySelectorAll('.bookmark_product');
let imgBookmarkActiv = '/catalog/view/theme/gaswodsnab/image/icons/bookmark_product_active.png';
let imgBookmarkPassive = 'http://gaswodsnab/catalog/view/theme/gaswodsnab/image/icons/bookmark_product.png';

iconCompair.forEach(el => {
    el.onclick = function () {
        if (el.childNodes[1].src == imgCompairPassive) {
            el.childNodes[1].src = imgCompairActiv
        } else {
            el.childNodes[1].src = imgCompairPassive
        }    
    }
});

iconBookmark.forEach(el => {
    el.onclick = function () {
        if (el.childNodes[1].src == imgBookmarkPassive) {
            el.childNodes[1].src = imgBookmarkActiv
        } else {
            el.childNodes[1].src = imgBookmarkPassive
        }    
    }
});


