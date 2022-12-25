function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

function checkValue() {
    var searchKey = document.getElementById("post_search").value;
    var searchBtn = document.getElementById("search_btn");
    if (searchKey != '') {
        searchBtn.classList.add("active");

    } else {
        searchBtn.classList.remove("active");
    }
}

function dimOverlay(ope) {
    if (ope == "add") {
        document.getElementById('dim-overlay').classList.remove('hidden')
    } else if (ope == "remove") {
        document.getElementById('dim-overlay').classList.add('hidden')
    }
}

function SideNav(ope) {
    if (ope == "show") {
        document.getElementById("sidenav").style.width = "265px";
        dimOverlay("add");
    } else if (ope == "hide") {
        document.getElementById("sidenav").style.width = "0"
        dimOverlay("remove");
    }
}

function SearchBar() {
    document.getElementById("search_box").style.width = "100%";
    document.getElementById("search_ico").style.left = "0"
    document.getElementById("search_btn").style.display = 'block';
}

function copy(id) {
    var range = document.createRange();
    var click_btn = document.getElementById(id + "_copy")
    range.selectNode(document.getElementById(id));
    window.getSelection().removeAllRanges(); // clear current selection
    window.getSelection().addRange(range); // to select text
    document.execCommand("copy");
    window.getSelection().removeAllRanges();// to deselect
    click_btn.innerHTML = "copied!"
    sleep(1500).then(() => {
        click_btn.innerHTML = "copy"
    });
}

function handleMousePos(event) {
    var mouseClickWidth = event.clientX;
    var mouseClickHeight = event.clientY;
    if (mouseClickWidth >= 270) {
        document.getElementById("sidenav").style.width = "0"
        dimOverlay("remove");
    }
    if (mouseClickHeight >= 65) {
        document.getElementById("search_ico").style.left = "95%"
        document.getElementById("search_box").style.width = "0";
        document.getElementById("search_btn").style.display = 'none';
    }
}

document.addEventListener("click", handleMousePos);