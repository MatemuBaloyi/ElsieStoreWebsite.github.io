function showPopover(message, type = 'success') {
  const popover = document.getElementById("popover");
  if (!popover) return;

  popover.style.background = type === 'error' ? '#ef4444' : '#22c55e';
  popover.textContent = message;
  popover.style.display = "block";

  setTimeout(() => {
    popover.style.display = "none";
  }, 3000);

}

