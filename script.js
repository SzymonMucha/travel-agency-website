const alertPlaceholder = document.getElementById('alertPlaceholder')

const customAlert = (message, type) => {
  alertPlaceholder.innerHTML += 
  `<div class="alert alert-${type} alert-dismissible" role="alert">
     <div>${message}</div>
     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>`;
}
/*
<div class="alert alert-${type} alert-dismissible" role="alert">
       <div>${message}</div>
       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>*/
//customAlert('Działam!', 'success');

function dateValidation() {
    let today = new Date();
    let dd = today.getDate() + 7;
    let mm = today.getMonth() + 1; //January is 0!
    let yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("datefield").setAttribute("min", today);
}

let r = document.documentElement;
localStorage.setItem("darkModeFlag", "0");

function price() {
    let ppl = parseInt(document.getElementsByName("howManyPpl")[0].value);
    let insurance = document.getElementsByName("isInsurance")[0].checked;
    let price = ((insurance ? 800 : 0) * ppl) + (ppl * (getCookie("tripKind") == "0" ? 417 : 1687));

    document.getElementById("priceBtn").innerHTML = (isNaN(price) ? "Zarezerwuj" : price + "zł");
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        if (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

const isLogged = () => getCookie("islogged") != "";
const logout = () => {
    document.cookie = "islogged=";
    customAlert("Logged out", 'success');
}
const reservation = k => {
    document.cookie = `tripKind=${parseInt(k)}`;//0 - group, 1 - individual
    window.location.replace("res_preview.php");
}
function requireBeingLogged(func, arg = '') {
    console.log(isLogged());
    if (isLogged())
        func(arg);
    else {
        window.location.replace("login_preview.php");
    }
}

const darkModeOnLoad = () => {
    if (isNaN(parseInt(getCookie("darkModeFlag")))) {
        document.cookie = "darkModeFlag=0";
    }
    let flag = parseInt(getCookie("darkModeFlag"));

    //colors change
    if (flag == 0) {
        var rstyle = getComputedStyle(r);
        var font_color = "#2B3A03";
        var bg_color = "#C7AA95";
        var font_r_color = "#C7AA95";
        var bg_r_color = "#2B3A03";
    }
    if (flag == 1) {
        var rstyle = getComputedStyle(r);
        var font_color = "#C7AA95";
        var bg_color = "#2B3A03";
        var font_r_color = "#2B3A03";
        var bg_r_color = "#C7AA95";
    }

    r.style.setProperty('--ft-color', font_color);
    r.style.setProperty('--bg-color', bg_color);
    r.style.setProperty('--ft-color-reversed', font_r_color);
    r.style.setProperty('--bg-color-reversed', bg_r_color);

    //logo change
    document.getElementById("logo").src = `logo${flag}.png`;
    if (document.getElementById("loginLogo"))
        document.getElementById("loginLogo").src = `logo${flag}.png`;

    //button icon change
    if (flag)
        document.getElementById("darkmode").innerHTML = "&#xe518;";
    else
        document.getElementById("darkmode").innerHTML = "&#xe51c;";
}

const darkModeOnClick = () => {
    let flag = parseInt(getCookie("darkModeFlag"));

    if (flag == 1)
        document.cookie = "darkModeFlag=0";
    if (flag == 0)
        document.cookie = "darkModeFlag=1";

    darkModeOnLoad();
}

document.addEventListener("DOMContentLoaded", darkModeOnLoad);
document.getElementById("darkmode").addEventListener("click", darkModeOnClick);