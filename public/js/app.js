var oldTab = 3;
function changeTab(tab) {
    if (tab == oldTab) return;
    if (document.getElementById('tab'+tab).classList.contains('footer-item-disabled')) return;
    document.getElementById('tab'+tab).classList.add('footer-item-selected');
    document.getElementById('tab'+oldTab).classList.remove('footer-item-selected');
    oldTab = tab;
    if (tab == 1) { //Ubicaciones

    } else if (tab == 2) { //Gincanas

    } else { //Mi actividad

    }
}

function disableTab(tab) {
    document.getElementById('tab'+tab).classList.add('footer-item-disabled');
    if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_map.png";
    } else if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_gincanas.png";
    } else {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_activity.png";
    }
}

function enableTab(tab) {
    if (!document.getElementById('tab'+tab).classList.contains('footer-item-disabled')) return;
    
    document.getElementById('tab'+tab).classList.remove('footer-item-disabled');
    if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_map_selected.png";
    } else if (tab == 1) {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_gincanas_selected.png";
    } else {
        document.getElementById('tab'+tab).getElementsByTagName('img')[0].src = "../img/icon_activity_selected.png";
    }
}

function loading(b) {
    if (b) {
        document.getElementById('load').style.display = 'block';
    } else {
        document.getElementById('load').style.display = 'none';
    }
}