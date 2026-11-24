/* ==========================================================================
   NAVORA — public/js/script.js
   Progressive enhancement only. Every flow works without JavaScript except
   the modal/drawer affordances, which are pure presentation.

   No dependencies, no build step, no analytics.
   ========================================================================== */

(function () {
  "use strict";

  var FOCUSABLE = [
    "a[href]",
    "button:not([disabled])",
    "input:not([disabled]):not([type='hidden'])",
    "select:not([disabled])",
    "textarea:not([disabled])",
    "[tabindex]:not([tabindex='-1'])"
  ].join(",");

  var body = document.body;

  /* ------------------------------------------------------------------------
     Utilities
     ------------------------------------------------------------------------ */

  function focusables(root) {
    if (!root) {
      return [];
    }
    return Array.prototype.filter.call(root.querySelectorAll(FOCUSABLE), function (el) {
      return el.offsetParent !== null || el === document.activeElement;
    });
  }

  function firstFocusable(root) {
    var preferred = root.querySelector("[data-nv-autofocus]");
    if (preferred && typeof preferred.focus === "function") {
      return preferred;
    }
    var list = focusables(root);
    return list.length ? list[0] : null;
  }

  function trapTab(event, root) {
    if (event.key !== "Tab") {
      return;
    }
    var list = focusables(root);
    if (!list.length) {
      event.preventDefault();
      return;
    }
    var first = list[0];
    var last = list[list.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  }

  function lockScroll(lock) {
    if (lock) {
      body.classList.add("nv-scroll-lock");
      body.style.overflow = "hidden";
    } else if (!document.querySelector(".nv-drawer:not([hidden])") && !document.querySelector(".nv-dialog:not([hidden])")) {
      body.classList.remove("nv-scroll-lock");
      body.style.overflow = "";
    }
  }

  /* ------------------------------------------------------------------------
     Drawer (app navigation + public menu)
     ------------------------------------------------------------------------ */

  var lastDrawerTrigger = null;

  function openDrawer(drawer, trigger) {
    if (!drawer) {
      return;
    }
    lastDrawerTrigger = trigger || document.activeElement;
    drawer.hidden = false;

    var backdrop = document.querySelector('[data-nv-drawer-close="' + drawer.id + '"]');
    if (backdrop) {
      backdrop.hidden = false;
    }

    if (trigger) {
      trigger.setAttribute("aria-expanded", "true");
    }

    lockScroll(true);
    var target = firstFocusable(drawer);
    if (target) {
      target.focus();
    }
  }

  function closeDrawer(drawer) {
    if (!drawer) {
      return;
    }
    drawer.hidden = true;

    var backdrop = document.querySelector('[data-nv-drawer-close="' + drawer.id + '"]');
    if (backdrop) {
      backdrop.hidden = true;
    }

    document.querySelectorAll('[data-nv-drawer-open="' + drawer.id + '"]').forEach(function (btn) {
      btn.setAttribute("aria-expanded", "false");
    });

    lockScroll(false);

    if (lastDrawerTrigger && typeof lastDrawerTrigger.focus === "function") {
      lastDrawerTrigger.focus();
    }
    lastDrawerTrigger = null;
  }

  document.addEventListener("click", function (event) {
    var opener = event.target.closest("[data-nv-drawer-open]");
    if (opener) {
      event.preventDefault();
      openDrawer(document.getElementById(opener.getAttribute("data-nv-drawer-open")), opener);
      return;
    }

    var closer = event.target.closest("[data-nv-drawer-close]");
    if (closer) {
      event.preventDefault();
      var drawer = document.getElementById(closer.getAttribute("data-nv-drawer-close"));
      if (drawer && !drawer.hidden) {
        closeDrawer(drawer);
      }
      return;
    }

    var hashLink = event.target.closest(".nv-drawer a[href^='#']");
    if (hashLink) {
      var parent = hashLink.closest(".nv-drawer");
      if (parent) {
        closeDrawer(parent);
      }
    }
  });

  document.addEventListener("keydown", function (event) {
    var drawer = document.querySelector(".nv-drawer:not([hidden])");
    if (!drawer) {
      return;
    }
    if (event.key === "Escape") {
      event.preventDefault();
      closeDrawer(drawer);
      return;
    }
    trapTab(event, drawer);
  });

  /* ------------------------------------------------------------------------
     Dialog
     ------------------------------------------------------------------------ */

  var lastDialogTrigger = null;

  function openDialog(dialog, trigger) {
    if (!dialog) {
      return;
    }
    lastDialogTrigger = trigger || document.activeElement;
    dialog.hidden = false;
    lockScroll(true);

    var target = firstFocusable(dialog);
    if (target) {
      target.focus();
    }
  }

  function closeDialog(dialog) {
    if (!dialog) {
      return;
    }
    dialog.hidden = true;
    lockScroll(false);

    if (lastDialogTrigger && typeof lastDialogTrigger.focus === "function") {
      lastDialogTrigger.focus();
    }
    lastDialogTrigger = null;
  }

  document.addEventListener("click", function (event) {
    var opener = event.target.closest("[data-nv-dialog-open]");
    if (opener) {
      event.preventDefault();
      openDialog(document.getElementById(opener.getAttribute("data-nv-dialog-open")), opener);
      return;
    }

    var closer = event.target.closest("[data-nv-dialog-close]");
    if (closer) {
      event.preventDefault();
      closeDialog(closer.closest(".nv-dialog"));
    }
  });

  document.addEventListener("keydown", function (event) {
    var dialog = document.querySelector(".nv-dialog:not([hidden])");
    if (!dialog) {
      return;
    }
    if (event.key === "Escape") {
      event.preventDefault();
      closeDialog(dialog);
      return;
    }
    trapTab(event, dialog);
  });

  // Dialogs rendered open on the server (e.g. after a validation error).
  document.querySelectorAll("[data-nv-dialog-autoopen]").forEach(function (dialog) {
    dialog.hidden = false;
    lastDialogTrigger = null;
    lockScroll(true);
    var target = firstFocusable(dialog);
    if (target) {
      target.focus();
    }
  });

  /* ------------------------------------------------------------------------
     Confirmation dialog
     ------------------------------------------------------------------------ */

  var confirmDialog = document.getElementById("nv-confirm-dialog");

  document.addEventListener("click", function (event) {
    var trigger = event.target.closest("[data-nv-confirm]");
    if (!trigger || !confirmDialog) {
      return;
    }
    event.preventDefault();

    var form = confirmDialog.querySelector("[data-nv-confirm-form]");
    var title = confirmDialog.querySelector("#nv-confirm-title");
    var message = confirmDialog.querySelector("#nv-confirm-message");
    var submit = confirmDialog.querySelector("#nv-confirm-submit");

    if (form) {
      form.setAttribute("action", trigger.getAttribute("data-nv-confirm-action") || "");
    }
    if (title) {
      title.textContent = trigger.getAttribute("data-nv-confirm-title") || "Konfirmasi";
    }
    if (message) {
      message.textContent = trigger.getAttribute("data-nv-confirm-message") || "";
    }
    if (submit) {
      submit.textContent = trigger.getAttribute("data-nv-confirm-label") || "Lanjutkan";
      submit.className = "nv-btn " + (trigger.getAttribute("data-nv-confirm-variant") === "danger" ? "nv-btn--danger" : "nv-btn--primary");
    }

    // The confirm trigger lives inside a card menu that closes on click, so the
    // trigger itself is hidden. Return focus to the menu button instead.
    var menu = trigger.closest(".nv-menu");
    var returnTarget = menu ? menu.querySelector("[data-nv-menu]") : trigger;

    openDialog(confirmDialog, returnTarget);
  });

  /* ------------------------------------------------------------------------
     Card action menus
     ------------------------------------------------------------------------ */

  function closeMenu(menu, returnFocus) {
    var button = menu.querySelector("[data-nv-menu]");
    var list = menu.querySelector(".nv-menu__list");
    if (list) {
      list.hidden = true;
    }
    if (button) {
      button.setAttribute("aria-expanded", "false");
      if (returnFocus) {
        button.focus();
      }
    }
  }

  document.addEventListener("click", function (event) {
    var button = event.target.closest("[data-nv-menu]");
    if (button) {
      event.preventDefault();
      var menu = button.closest(".nv-menu");
      var list = menu ? menu.querySelector(".nv-menu__list") : null;
      if (!menu || !list) {
        return;
      }
      var isOpen = !list.hidden;

      document.querySelectorAll(".nv-menu__list").forEach(function (other) {
        if (other !== list) {
          other.hidden = true;
          var owner = other.closest(".nv-menu");
          var ownerBtn = owner ? owner.querySelector("[data-nv-menu]") : null;
          if (ownerBtn) {
            ownerBtn.setAttribute("aria-expanded", "false");
          }
        }
      });

      list.hidden = isOpen;
      button.setAttribute("aria-expanded", isOpen ? "false" : "true");
      if (!isOpen) {
        var firstItem = list.querySelector(".nv-menu__item");
        if (firstItem) {
          firstItem.focus();
        }
      }
      return;
    }

    var menu = event.target.closest(".nv-menu");
    if (!menu) {
      document.querySelectorAll(".nv-menu__list").forEach(function (list) {
        list.hidden = true;
        var owner = list.closest(".nv-menu");
        var ownerBtn = owner ? owner.querySelector("[data-nv-menu]") : null;
        if (ownerBtn) {
          ownerBtn.setAttribute("aria-expanded", "false");
        }
      });
    }
  });

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") {
      return;
    }
    var openMenu = document.querySelector(".nv-menu__list:not([hidden])");
    if (openMenu) {
      event.preventDefault();
      closeMenu(openMenu.closest(".nv-menu"), true);
    }
  });

  /* ------------------------------------------------------------------------
     Dismissible alerts
     ------------------------------------------------------------------------ */

  document.addEventListener("click", function (event) {
    var button = event.target.closest("[data-nv-dismiss]");
    if (!button) {
      return;
    }
    var alert = button.closest(".nv-alert");
    if (alert) {
      alert.remove();
    }
  });

  /* ------------------------------------------------------------------------
     Password visibility
     ------------------------------------------------------------------------ */

  document.addEventListener("click", function (event) {
    var toggle = event.target.closest("[data-nv-password-toggle]");
    if (!toggle) {
      return;
    }
    var group = toggle.closest(".nv-input-group");
    var input = group ? group.querySelector("input") : null;
    if (!input) {
      return;
    }

    var show = input.type === "password";
    input.type = show ? "text" : "password";
    toggle.setAttribute("aria-pressed", show ? "true" : "false");
    toggle.setAttribute("aria-label", show ? "Sembunyikan kata sandi" : "Tampilkan kata sandi");

    var eyeShow = toggle.querySelector("[data-nv-eye-show]");
    var eyeHide = toggle.querySelector("[data-nv-eye-hide]");
    if (eyeShow) {
      eyeShow.hidden = show;
    }
    if (eyeHide) {
      eyeHide.hidden = !show;
    }
  });

  /* ------------------------------------------------------------------------
     Money input: digits in the field, formatted preview underneath
     ------------------------------------------------------------------------ */

  function formatRupiah(digits) {
    if (!digits) {
      return "";
    }
    return "Rp" + digits.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function bindMoney(input) {
    var preview = document.querySelector('[data-nv-money-preview="' + input.name + '"]');

    function sync() {
      var digits = input.value.replace(/\D+/g, "");
      // Strip leading zeros without turning "0" into an empty field mid-typing.
      digits = digits.replace(/^0+(?=\d)/, "");
      if (input.value !== digits) {
        input.value = digits;
      }
      if (preview) {
        preview.textContent = digits ? formatRupiah(digits) : "";
      }
    }

    input.addEventListener("input", sync);
    sync();
  }

  document.querySelectorAll("[data-nv-money]").forEach(bindMoney);

  /* ------------------------------------------------------------------------
     Character counter
     ------------------------------------------------------------------------ */

  document.querySelectorAll("[data-nv-counter]").forEach(function (counter) {
    var field = document.getElementById(counter.getAttribute("data-nv-counter"));
    if (!field) {
      return;
    }
    var limit = field.getAttribute("maxlength") || "";
    function sync() {
      counter.textContent = field.value.length + (limit ? " / " + limit : "");
    }
    field.addEventListener("input", sync);
    sync();
  });

  /* ------------------------------------------------------------------------
     Submit loading state
     ------------------------------------------------------------------------ */

  document.querySelectorAll("form[data-nv-submit-loading]").forEach(function (form) {
    form.addEventListener("submit", function () {
      if (form.dataset.nvSubmitting === "true") {
        return;
      }
      form.dataset.nvSubmitting = "true";
      form.setAttribute("aria-busy", "true");

      var button = form.querySelector('button[type="submit"]');
      if (!button) {
        return;
      }
      var label = button.textContent.trim();
      button.classList.add("is-loading");
      button.setAttribute("aria-disabled", "true");
      button.dataset.nvOriginalLabel = label;
      button.innerHTML =
        '<svg class="nv-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M21 12a9 9 0 1 1-6.2-8.6"/></svg>' +
        '<span class="nv-btn__label">Menyimpan…</span>';
    });
  });

  /* ------------------------------------------------------------------------
     Copy-to-clipboard (progressive, optional)
     ------------------------------------------------------------------------ */

  document.querySelectorAll("[data-nv-copy]").forEach(function (button) {
    button.addEventListener("click", function () {
      var value = button.getAttribute("data-nv-copy");
      if (!value || !navigator.clipboard) {
        return;
      }
      navigator.clipboard.writeText(value).then(function () {
        var original = button.textContent;
        button.textContent = "Tersalin";
        window.setTimeout(function () {
          button.textContent = original;
        }, 1600);
      });
    });
  });

  /* ------------------------------------------------------------------------
     Auto-submit GET filters (sort select)
     ------------------------------------------------------------------------ */

  document.querySelectorAll("[data-nv-autosubmit]").forEach(function (control) {
    control.addEventListener("change", function () {
      var form = control.form;
      if (form) {
        form.submit();
      }
    });
  });

  /* ------------------------------------------------------------------------
     Initial focus for server-rendered error summaries
     ------------------------------------------------------------------------ */

  var autoFocus = document.querySelector("[data-nv-autofocus]");
  if (autoFocus && typeof autoFocus.focus === "function") {
    autoFocus.focus();
  }
})();
