<fieldset>
    <div class="form-group">
        <label for="username">username *</label>
          <input type="text" name="username" value="<?php echo htmlspecialchars($edit ? $customer['username'] : '', ENT_QUOTES, 'UTF-8'); ?>"  class="form-control" required="required" id = "username" >
    </div> 
    <div class="form-group">
        <label for="">password</label>
          <textarea readonly name="value"  placeholder="password" class="form-control" id="value"><?php echo htmlspecialchars(($edit) ? $customer['value'] : GetRandomPwd(), ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>
    <input type="text" name="attribute" value="Cleartext-Password" hidden id = "attribute" >
    <input type="text" name="op" value=":="  hidden id = "op" >
    <div class="form-group">
        <label>group </label>
           <?php
           $db = getDbInstance();
           $db->setQueryOption('DISTINCT');
           $opt_arr = $db->ArrayBuilder()->get('radgroupcheck',null,'groupname'); 
                            ?>
            <select name="group" class="form-control selectpicker" required>
                <option value=" " >Please select a group</option>
                <?php
                foreach ($opt_arr as $opt) {
                    if ($edit && $opt['groupname'] == $customer['group']) {
                        $sel = "selected";
                    } else {
                        $sel = "";
                    }
                    echo '<option value="'.$opt['groupname'].'"' . $sel . '>' . $opt['groupname'] . '</option>';
                }

                ?>
            </select>
    </div>  

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning" >Save <span class="glyphicon glyphicon-send"></span></button>
    </div>            
</fieldset>
