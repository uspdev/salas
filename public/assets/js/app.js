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

function disable(id, id1) {
  document.getElementById(id).setAttribute("disabled", "");
  document.getElementById(id1).removeAttribute("disabled");
}