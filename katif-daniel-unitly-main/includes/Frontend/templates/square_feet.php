<div class="form-group">
    <label for="door_width">Your Door Width (in inches): <?php echo $this->error; ?></label><br>
    <input type="number" id="door_width" name="door_width" min="1" step="any"> <span>inches</span>
</div>
<br>
<div class="form-group">
    <label for="door_height">Your Door Height (in inches): <?php echo $this->error; ?></label><br>
    <input type="number" id="door_height" name="door_height" min="1" step="any"> <span>inches</span>
</div>
<br>
<div class="form-group">
    <h4 id="calc_area">Calculated Area: <span>0</span> sq-ft </h4>
    <input type="hidden" id="price_per_unit" name="price_per_unit" value="<?php echo $price_per_unit; ?>">
</div>
<hr>
<div class="form-group">
    <input type="checkbox" id="door_painting" name="door_painting"><label for="door_painting">Add Extra $<?php echo $painting_cost; ?>
        for painting.</label>
</div>