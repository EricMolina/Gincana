var picker = document.getElementById("labelColor")
var hexInput = document.getElementById("hexColor");
picker.addEventListener("change",()=>{hexInput.value = picker.value});
hexInput.addEventListener("keyup",()=>{
    if (hexInput.value.split("#").length != 2) {
        hexInput.value = `#${hexInput.value}`;
    }
    picker.value = hexInput.value;
});