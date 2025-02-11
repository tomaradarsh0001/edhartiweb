var totalAmountToPay = 0;
$(document).on("change", ".amountToPay", function () {
  var num = +$(this).val();
  var max = +$(this).attr("max");
  if (num <= 0 || (max > 0 && num > max)) {
    $(this).val("");
  }
  updateTotalAmount();
});
$(document).on("click", "#btnSubmitDemandPayment", function () {
  if (totalAmountToPay > 0)
    $(document).find("#form-part-2").removeClass("d-none");
});

function updateTotalAmount() {
  totalAmountToPay = 0;
  $(document)
    .find(".amountToPay")
    .each(function () {
      var num = parseFloat($(this).val());
      var max = parseFloat($(this).attr("max"));
      if (num != "" && num > 0 && num <= max) {
        totalAmountToPay += num;
      }
    });
  console.log(totalAmountToPay);
  $(document)
    .find("#totalAmountToPay")
    .text(`₹ ${customNumFormat(totalAmountToPay)}`);
}
