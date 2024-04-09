var oldTab = 3;
function changeTab(tab) {
    if (tab == oldTab) return;
    if (document.getElementById('tab'+tab).classList.contains('footer-item-disabled')) return;
    document.getElementById('tab'+tab).classList.add('footer-item-selected');
    document.getElementById('tab'+oldTab).classList.remove('footer-item-selected');
    oldTab = tab;
    if (tab == 1) { //Ubicaciones
        loadPointers('ubicaciones');
        document.getElementById('current-activity-button').style.display = 'none';
        document.getElementById('create-gincana-button').style.display = 'none';
    } else if (tab == 2) { //Gincanas
        loadPointers('gincanas');
        document.getElementById('current-activity-button').style.display = 'none';
        document.getElementById('create-gincana-button').style.display = 'flex';
    } else { //Mi actividad
        console.log('b')
        document.getElementById('current-activity-button').style.display = 'flex';
        document.getElementById('create-gincana-button').style.display = 'none';
        document.getElementById('current-activity-button').onclick = () => {
            openBottomContainer(true);
            displayCurrentActivityStatus();
        }
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

var howManyLoadings = 0;
function loading(b) {
    if (b) {
        howManyLoadings++;
        document.getElementById('load').style.display = 'flex';
    } else {
        howManyLoadings--;
        if (howManyLoadings < 0) howManyLoadings = 0;
        
        if (howManyLoadings > 0) return;
        document.getElementById('load').style.display = 'none';
    }
}

function openUserProfile(b) {
    if (b) {
        document.getElementById('user-profile-container').style.display = 'inline-block';
    } else {
        document.getElementById('user-profile-container').style.display = 'none';
    }
}

function openBottomContainer(b) {
    if (b) {
        document.getElementById('bottom-container').style.display = 'inline-block';
    } else {
        document.getElementById('bottom-container').style.display = 'none';
    }
}

function changeUserPhoto(user_id) {

}

function createUserLabel(user_id) {

}

function reloadContent() {
    loading(true);


    loading(false);
}