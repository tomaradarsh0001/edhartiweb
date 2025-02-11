// Minified by Diwakar Sinha at 01-01-2025
$(document).ready(function () {
  $(".marquee ul").on("mouseover", function () {
    $(this).css("animation-play-state", "paused");
  }),
    $(".marquee ul").on("mouseout", function () {
      $(this).css("animation-play-state", "running");
    });
});

// Diwakar Sinha -> Single/Multiple Input Validation 11-06-2024
// Minified by Diwakar Sinha at 01-01-2025
$(document).ready(function () {
  $(".alpha-only").keypress(function (t) {
    var a = t.which;
    (a < 65 || (a > 90 && a < 97) || a > 122) &&
      32 !== a &&
      46 !== a &&
      47 !== a &&
      t.preventDefault();
  }),
    $(".numericDecimal").on("input", function () {
      var t = $(this).val();
      /^\d*\.?\d*$/.test(t) || $(this).val(t.slice(0, -1));
    }),
    $(".numericOnly").on("input", function (t) {
      $(this).val(
        $(this)
          .val()
          .replace(/[^0-9]/g, "")
      );
    }),
    $(".alphaNum-hiphenForwardSlash").on("input", function () {
      var t = $(this)
        .val()
        .replace(/[^a-zA-Z0-9\-\/]/g, "");
      $(this).val(t);
    }),
    $(".date_format").on("input", function (t) {
      var a = $(this).val().replace(/\D/g, "");
      a.length > 8 && (a = a.substring(0, 8));
      var n = "";
      a.length > 0 && (n = a.substring(0, 2)),
        a.length >= 3 && (n += "-" + a.substring(2, 4)),
        a.length >= 5 && (n += "-" + a.substring(4, 8)),
        $(this).val(n);
    }),
    $(".plotNoAlpaMix").on("input", function () {
      var t = $(this)
        .val()
        .replace(/[^a-zA-Z0-9+\-/]/g, "");
      $(this).val(t);
    }),
    $(".pan_number_format").on("input", function (t) {
      for (
        var a = $(this).val().toUpperCase(), n = "", i = 0;
        i < a.length;
        i++
      ) {
        var e = a[i];
        if (i < 5) {
          if (!/[A-Z]/.test(e)) {
            !1;
            break;
          }
          n += e;
        } else if (i >= 5 && i < 9) {
          if (!/[0-9]/.test(e)) {
            !1;
            break;
          }
          n += e;
        } else if (9 === i) {
          if (!/[A-Z]/.test(e)) {
            !1;
            break;
          }
          n += e;
        }
      }
      a.length, $(this).val(n);
    }),
    $(".alphaNum_slash_modulus").on("input", function () {
      var t = $(this).val(),
        a = t.replace(/[^a-zA-Z0-9\/%]/g, "");
      t !== a && $(this).val(a);
    }),
    $(".alphanumericonly").keypress(function (t) {
      var a = t.which;
      (a >= 65 && a <= 90) ||
        (a >= 97 && a <= 122) ||
        (a >= 48 && a <= 57) ||
        32 === a ||
        46 === a ||
        47 === a ||
        t.preventDefault();
    });
});
// End

function getBaseURL() {
  const { protocol, hostname, port } = window.location;
  return `${protocol}//${hostname}${port ? ":" + port : ""}`;
}
