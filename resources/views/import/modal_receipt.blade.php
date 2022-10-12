<div class="modal fade" id="modal-receipt" >
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header printPageButton">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-excel-o"></i> <span
                        id="modal-title">{{lang('invoice')}}</span></h4>
            </div>

            <div class="modal-body myDivToPrint " >

                <div id="wrappers">
                    <h2 class="text-uppercase text-center" id="text-title"> Invoice KS4</h2>
                    <span>No : IN<span id="invoice"></span></span><br>
                    <span>Date :  <span id="t-date"></span></span><br>
                    <span>Branch : <span id="branch"></span> </span> <br> ​

                    <div >
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="row" style="border: 1px solid black !important"> ប្រភេទ</th>
                                <th style="border: 1px solid black !important">ចំនួន
                                </th>
                                <th style="border: 1px solid black !important">បរិយាយ
                                </th>
                            </tr>
                            </thead>
                            <tbody id="t-body"></tbody>
                            <tfoot>
                            <tr>
                                <td style="border: 1px solid black !important">សរុប</td>
                                <td colspan="2" id="t-total" style="border: 1px solid black !important"> 0 PCS
                                </td>
                            </tr>
                            </tfoot>

                        </table>

                        <table class="table">
                            <tbody>
                            <tr>
                                <td width="50%" style="border-top: unset">
                                    <div style="border-bottom: 1px solid black !important;">អ្នកប្រគល់ :</div>
                                </td>
                                <td width="50%" style="border-top: unset">
                                    <div style="border-bottom: 1px solid black !important;">អ្នកទទួល :</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <p class="text-center">សូមពិនិត្យទំនិញដែលទទួលបាន សូមអរគុណ !</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer printPageButton">
                <button type="button" onclick="window.print()" class="btn btn-default"><i
                        class="fa fa-print"></i> {{lang('print')}}</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
