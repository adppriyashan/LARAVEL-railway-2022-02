<div class="modal fade" id="seasonprint" tabindex="-1" role="dialog" aria-labelledby="seasonprintModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="print_content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="seasonqrprint()" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymodal" tabindex="-1" role="dialog" aria-labelledby="paymodalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymodalLabel">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group"> <label for="username">
                        <h6>Card Owner</h6>
                    </label> <input id="username" type="text" name="username" placeholder="Card Owner Name" required
                        class="form-control "> </div>
                <div class="form-group"> <label for="cardNumber">
                        <h6>Card number</h6>
                    </label>
                    <div class="input-group"> <input type="text" id="cardNumber" name="cardNumber" placeholder="Valid card number"
                            class="form-control " required>
                        <div class="input-group-append"> <span class="input-group-text text-muted"> <i
                                    class="fab fa-cc-visa mx-1"></i> <i class="fab fa-cc-mastercard mx-1"></i> <i
                                    class="fab fa-cc-amex mx-1"></i> </span> </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group"> <label><span class="hidden-xs">
                                    <h6>Expiration Date</h6>
                                </span></label>
                            <div class="input-group"> <input id="cardMonth" type="number" placeholder="MM" name=""
                                    class="form-control" required> <input id="cardYear" type="number" placeholder="YY" name=""
                                    class="form-control" required> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-4"> <label data-toggle="tooltip"
                                title="Three digit CV code on the back of your card">
                                <h6>CVV <i class="fa fa-question-circle d-inline"></i></h6>
                            </label> <input id="cvv" type="text" required class="form-control"> </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="paybtn" class="btn btn-primary">Confirm Payment</button>
            </div>
        </div>
    </div>
</div>

<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Railway Master</span>
        </div>
    </div>
</footer>
