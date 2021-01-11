<?php include './components/bootstrap.php'; ?>
<?php include './components/jquery.php'; ?>
<?php include './components/multiselect.php'; ?>
<?php include './components/navigation.php'; ?>

<link rel="stylesheet" href="./common/css/forms.css">

<div style="padding: 50px 0px 50px 0px;">
    <div class="container">
      
    <h2 style="font-weight:bold;">Send Reports</h2>

      <form class="form-horizontal" method="POST" action="email/post.php">

        <div class="form-group">
          <label for="colFormLabel" class="col-sm-4 col-form-label">Select Group</label>
          <div class="col-sm-12">
            <div class="form-gray-bg">
              <select id="example-getting-started" multiple="multiple" name="selected[]">
                <optgroup label="Academic Affairs">
                  <option value="AA-APGS">APGS</option>
                  <option value="AA-ARSP">ARSP</option>
                  <option value="AA-ATS">ATS</option>
                  <option value="AA-COB">COB</option>
                  <option value="AA-CPIE">CPIE</option>
                  <option value="AA-CED">CED</option>
                  <option value="AA-CHHS">CHHS</option>
                  <option value="AA-CLA">CLA</option>
                  <option value="AA-CNSM">CNSM</option>
                  <option value="AA-COE">COE</option>
                  <option value="AA-COTA">COTA</option>
                  <option value="AA-DIV">DIV</option>
                  <option value="AA-ES">ES</option>
                  <option value="AA-FA">FA</option>
                  <option value="AA-LIB">LIB</option>
                  <option value="AA-OSI">OSI</option>
                  <option value="AA-RSCH">RSCH</option>
                  <option value="AA-USAA">USAA</option>
                </optgroup>
                <optgroup label="Administration & Finance">
                  <option value="DAF-FM">FM</option>
                  <option value="DAF-HRM">HRM</option>
                  <option value="DAF-PPFM">PPFM</option>
                  <option value="DAF-UP">UP</option>
                  <option value="DAF-VPAF">VPAF</option>
                </optgroup>
                <optgroup label="Other">
                  <option value="ATH">ATH</option>
                  <option value="DOIT">DOIT</option>
                  <option value="PRES">PRES</option>
                  <option value="DSA">DSA</option>
                  <option value="URD">URD</option>
                </optgroup>
                <optgroup label="Test">
                  <option value="ITS">ITS</option>
                  <option value="ATI">ATI</option>
                </optgroup>
              </select>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="exampleFormControlTextarea1" class="col-sm-2 col-form-label">Message</label>
          <div class="col-sm-12">
            <textarea name="email-body" class="form-control form-gray-bg" id="exampleFormControlTextarea1" rows="3"></textarea>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-10">
              <button type="submit" class="btn btn-default" id="submit-btn">Submit</button>
          </div>
        </div>

      </form>
      
    </div>
</div>

<?php include './components/footer.php'; ?>

<!-- http://davidstutz.de/bootstrap-multiselect -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect({
          includeSelectAllOption: true,
          enableClickableOptGroups: true,
          enableCollapsibleOptGroups: true,
          collapseOptGroupsByDefault: true,
          buttonWidth: '100%',
          maxHeight: 300,
        });

        $(".caret-container").append("<b class=\"bryan-caret\"></b>");
    });
</script>

