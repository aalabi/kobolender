const formatToCurrency = (amount) => {
  return amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
};

$(document).ready(function () {
  function calDiscountAmt(quantity, price, discount, absolute = false) {
    let previousAmount = $("#al-finalPrice").val().trim() || "0";
    previousAmount = parseFloat(previousAmount.replace(",", ""));
    quantity = quantity || 0;
    price = price || 0;
    discount = discount || 0;
    let discountAmount;
    if (absolute) {
      discountAmount = parseFloat(previousAmount) - parseFloat(discount);
    } else {
      discountAmount = quantity * price - discount;
    }
    return discountAmount;
  }

  let devMode = true;
  let url = devMode
    ? "http://localhost/sah-silga/"
    : "https://caibkeeper.sahsilgafarms.com/";

  $(".al-confirm").click(function (event) {
    if (!confirm("Are you sure you want to proceed")) {
      event.preventDefault();
    }
  });

  $("#addPromoter").click(function () {
    let promoterRow = `
        <div class="border border-primary p-1 mb-2">
            <button class="remove-promoter" type="button" class="btn btn-sm btn-danger" 
            style="color: #fff; border-color: #f00; padding: 0.25rem 0.5rem; font-size: .875rem; border-radius: 0.2rem;
            background-color: #f00">-</button>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">Promoter's Name *</label>
                <div class="col-md-8">
                    <input required type="text" name="promoter_name_col[]" class="form-control">
                </div>
            </div>
            <input required type='hidden' name='bvn_nin_col[]' class='al-regular-input' required value='BVN'>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">BVN *</label>
                <div class="col-md-8">
                    <input required type="text" name="bvn_nin_no_col[]" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">
                    Bank Statement * 
                    <small>6 months (png, jpg, doc, pdf max 1mb)</small>
                </label>
                <div class="col-md-8">
                    <input type="file" required id="myFile" name="promoter_bank_statement_col[]" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">
                    Guarantees Letter * 
                    <small>(png, jpg, doc, pdf max 1mb)</small>
                </label>
                <div class="col-md-8">
                    <input required type="file" id="myFile" name="letter_of_guarantor_col[]" class="form-control al-regular-input">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">
                    ID Card *
                    <small>(png, jpg, doc, pdf max 1mb)</small>
                </label>
                <div class="col-md-8">
                    <input required type="file" required id="myFile" name="promoterIdCard[]" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-4 col-form-label">
                    Passport Picture *
                    <small>(png, jpg, doc, pdf max 1mb)</small>
                </label>
                <div class="col-md-8">
                    <input required type="file" id="myFile" name="promoterPassport[]" class="form-control al-regular-input">
                </div>
            </div>
        </div>
    `;
    $("#promoterContainer").append(promoterRow);

    $(".remove-promoter").click(function () {
      $(this).parent().remove();
    });
  });
});
