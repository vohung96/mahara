<script>
    $(function() {
        $("#datepicker").datepicker({
        	showOn: "button",
            buttonImage: "{$WWWROOT}theme/raw/static/images/btn_calendar.png",
            buttonImageOnly: true,
            dateFormat: "{str section='artefact.calendar' tag='datepicker_format'}",
            altField: "#alternate",
            altFormat: "yy/mm/dd",
            numberOfMonths: 2,
            constrainInput: true,
            firstDay:  "{str section='artefact.calendar' tag='datepicker_firstday'}",
            dayNamesMin: 	[ "{str section='artefact.calendar' tag='sunday_short'}", 
            				"{str section='artefact.calendar' tag='monday_short'}", 
            				"{str section='artefact.calendar' tag='tuesday_short'}", 
            				"{str section='artefact.calendar' tag='wednesday_short'}", 
            				"{str section='artefact.calendar' tag='thursday_short'}", 
            				"{str section='artefact.calendar' tag='friday_short'}", 
            				"{str section='artefact.calendar' tag='saturday_short'}" ],
           monthNames: 		[ "{str section='artefact.calendar' tag='1'}", 
           					"{str section='artefact.calendar' tag='2'}", 
           					"{str section='artefact.calendar' tag='3'}", 
           					"{str section='artefact.calendar' tag='4'}", 
           					"{str section='artefact.calendar' tag='5'}", 
           					"{str section='artefact.calendar' tag='6'}", 
           					"{str section='artefact.calendar' tag='7'}", 
           					"{str section='artefact.calendar' tag='8'}", 
           					"{str section='artefact.calendar' tag='9'}", 
           					"{str section='artefact.calendar' tag='10'}", 
           					"{str section='artefact.calendar' tag='11'}", 
           					"{str section='artefact.calendar' tag='12'}" ]
        });
    });
</script>
<style type="text/css">
	input#datepicker, input#edittask_title {
		height: 15px;
		vertical-align: top;
	}

	input#datepicker {
		margin-right: 5px;
	}

	input#edittask_completed {
		margin-top: 0px;
	}
</style>
<div id='taskoverlay'>
	<div id='overlay'></div>
	<div id='overlay_window' class="overlay">
		<div class="overlay_control" style='min-width:0;'>
    		<img src="{$WWWROOT}theme/raw/static/images/btn_close.png" class="deletebutton" alt="X" onclick='hide_overlay("taskoverlay");'/>
        </div>
        <div id="overlay_content">
			<form name="edittask" method="get" action="" id="edittask"> 						
				{if $missing_title == 1}
					<p class="errmsg">{str section="artefact.calendar" tag='missing_title'}</p>
				{/if}
				{if $missing_date == 1}
					<p class="errmsg">{str section="artefact.calendar" tag='missing_date'}</p>
				{/if}
				{if $specify_parent == 1}
					<p>
						<label for="edittask_title">{str section="artefact.plans" tag='plan'}</label>
						<span class="requiredmarker">*</span><br/>
						<select class="required" id="edittask_parent" name="parent_id">
							{foreach from=$plans.data item=plan}
							{assign var=id value=$plan->id}
								<option value='{$id}'>{$plan->title}</option>
							{/foreach}
						</select>
					</p>
				{/if}
				<p>
					<label for="edittask_title">{str section="artefact.calendar" tag='title'}</label>
					<span class="requiredmarker">*</span><br/>
					<input type="text" class="required text autofocus" id="edittask_title" name="title" size="30" tabindex="1" value="{$form['title']}">
				</p>
				<p>
				<label for="edittask_completiondate">{str section="artefact.calendar" tag='completiondate'}</label> 
				<span class="requiredmarker">*</span><br/>
				<input type="text" id="datepicker" value="{$form['completiondate_display']}">
				<input type="hidden" name="completiondate" id="alternate" value="{$form['completiondate']}">
				</p>

				<p class="description">{str section="artefact.calendar" tag='format'}</p> 
				<p>
				 	<label for="edittask_description">{str section="artefact.calendar" tag='description'}</label><br/>
				 	<textarea rows="5" cols="50" class="textarea" id="edittask_description" name="description" tabindex="1">{$form['description']}</textarea>
				</p>		
				<p>
					<label for="edittask_completed">{str section="artefact.calendar" tag='completed'}</label>
					<input type="checkbox" class="checkbox" id="edittask_completed" name="completed" tabindex="1"
					{if $form['completed'] == '1'}
						checked
					{/if}
					>
					<input type="hidden" name="task" value="{$edit_task_id}"/>
					{if $specify_parent == 0}
						<input type="hidden" name="parent_id" value="{$parent_id}"/>
					{/if}
					<input type="hidden" name="task_info" value="{$edit_task_id}"/>
					<input type="hidden" name="type" value="task"/>
					<input type="hidden" name="month" value="{$month}"/>
					<input type="hidden" name="year" value="{$year}"/>
				</p>
				<p>
					<input type="submit" class="submitcancel submit" id="edittask_submit" name="submit" tabindex="1" value="{str section="artefact.calendar" tag='savetask'}">
				</p>
			</form> 
		</div>
	</div>
</div>