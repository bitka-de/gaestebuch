function closeToast() {
  const toast = document.getElementById("toast");
  if (toast) toast.remove();
}

window.addEventListener("DOMContentLoaded", () => {
  const toast = document.getElementById("toast");
  if (toast) {
    setTimeout(() => {
      toast.remove();
    }, 3000); 
  }
});
