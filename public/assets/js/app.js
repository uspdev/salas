function show(id) {
    document.getElementById(id).style.display = "flex";
  }

function hide(id) {
    document.getElementById(id).style.display = "none";
}

function showHide(id, id1) {
  show(id);
  hide(id1);
}