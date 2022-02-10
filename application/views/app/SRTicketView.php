<div class="py-2 px-5">
    <div class="text-center" id="imgProfile">
        <i class="fas fa-user-circle fa-7x"></i>
    </div>
    <section>
        <h4 class="text-center font-weight-bold text-primary">Transport Ticket</h4>
        <h5 class="text-center font-weight-bold text-primary" id="locate">SR-WC</h5>
        <span class="d-none" id="locateCode">SR</span>
    </section>
    <form class="user" method="POST" autocomplete="off" novalidate>
        <div class="input-group mb-3">
            <input type="text" name="enterId" id="enterId" class="form-control form-control-user" placeholder="Enter Employee ID" required>
            <div class="input-group-prepend">
                <button type="button" class="btn btn-warning btn-sm font-weight-bold" onclick="resetData();">
                    <i class="fas fa-sync-alt"></i> Clear
                </button>
            </div>
        </div>
    </form>

    <hr class="mb-0">

    <table class="table table-borderless table-sm py-0 my-0">
        <tbody class="font-weight-bold">
            <tr>
                <td class="text-right" style="width: 125px"><strong>ID:</strong></td>
                <td colspan="3" class="text-nowrap"><span id="infoEmpId">-</span></td>
            </tr>
            <tr>
                <td class="text-right"><strong>Name:</strong></td>
                <td colspan="3" class="text-nowrap"><span id="infoEmpName">-</span></td>
            </tr>
            <tr>
                <td class="text-right"><strong>Position:</strong></td>
                <td colspan="3" class="text-truncate" style="max-width: 60px;">
                    <span id="infoJob"></span>
                </td>
            </tr>
            <tr>
                <td class="text-right"><strong>Dept:</strong></td>
                <td colspan="2"><span id="infoDept"></span></td>
                <td class="text-nowrap text-center">Tier:&emsp;<span id="infoTier"></span></td>
            </tr>
            <tr>
                <td class="text-right"><strong>Trip:</strong></td>
                <td>
                    <span id="infoCounter" class="badge badge-success">0</span>
                </td>
                <td class="text-nowrap text-right">Use for:</td>
                <td class="text-nowrap text-right">
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="form-check-label" for="p">
                                <input type="radio" class="form-check-input" name="infoUseFor" id="p" value="Personal">Personal
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label" for="w">
                                <input type="radio" class="form-check-input" name="infoUseFor" id="w" value="Work">Work
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-right" title="Person(s)" style="vertical-align: middle"><strong>Copy:</strong></td>
                <td colspan="3">
                    <div class="input-group" style="width: 130px;">
                        <div class="input-group-prepend" onClick="decreaseNumber();">
                            <span class="input-group-text text-white hand bg-danger" id="inputGroup-sizing-sm"><i class="fas fa-minus"></i></span>
                        </div>
                        <input type="number" class="form-control text-center" value="1" min="1" max="10" id="infoCopy">
                        <div class="input-group-prepend" onClick="increaseNumber();">
                            <span class="input-group-text text-white hand bg-danger" id="inputGroup-sizing-sm"><i class="fas fa-plus"></i></span>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-center">
                    <hr class="sidebar-divider">

                    <button class="btn btn-primary m-0" id="btnPrint" onClick="submitTicket();" disabled>Print Ticket</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

