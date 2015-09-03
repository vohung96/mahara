<div id='task_list_day{$day}' class="disp_none">
	<div id='overlay'></div>
	<div id='overlay_window' class="overlay">
		<div class="overlay_control" style="min-width:0;">
		    <a onclick="document.getElementById('task_list_day{$day}').style.display='none';"> 
		    	<img src="{$WWWROOT}{$img}btn_close.png" alt="X"/>
		    </a>
		</div>
		<div id="overlay_content">
			<h3>{$full_dates[$day]}</h3>
			<div class="overflow" style="height:300px;">
                {foreach from=$plans.data item=plan}
                	{assign var=plan_id value=$plan->id}
                	<div name="task{$plan_id}">
	                	<div name="task{$plan_id}" class="planbox" style='background-color:#{$colors[$plan_id]};'></div>
	                    <div>
	                        {$plan->title}
	                    </div>
						{foreach from=$task_per_day[$day] item=task}
							{if $plan_id==$task['parent_id']}
								<a style="padding: 3px;" class="taskname" name="task{$task['parent_id']}" href='{$WWWROOT}{$cal}index.php?month={$month}&year={$year}&task_info={$task['task_id']}' title="{$task['full_title']}">{str tag="Task" section="artefact.plans"}: {$task['full_title']}
									{if $task['completed'] == '1'}
										<img style="vertical-align:middle" name="task{$task['parent_id']}" src='{$WWWROOT}theme/raw/static/images/success_small.png' alt='done' />
									{/if}
								</a>
							{/if}
						{/foreach}

						{foreach from=$event_per_day[$day] item=event}
							{if $plan_id==$event['parent_id']}
								<a style="padding: 3px;" class="taskname" name="task{$event['parent_id']}" href='{$WWWROOT}{$cal}index.php?month={$month}&year={$year}&event_info={$event['event_id']}' title="{$event['full_title']}">{str tag="event" section="artefact.calendar"}: {$event['full_title']}
								</a>
							{/if}
						{/foreach}
						<hr>
					</div>
					<div class="description description_overlay {if $day == $today}bggrey{elseif (($week_count == 0 or $week_count == 6) and $week_start == 0) or (($week_count == 5 or $week_count == 6) and $week_start == 1)}bgweekend{else}bgday{/if}">
					</div>
				{/foreach}
			</div>
			<div class="description txtcenter" ><div id="display_number_overlay{$day}" style="display:inline;" >{$number_of_tasks_and_events_per_day[$day]}</div> {str section="artefact.calendar" tag='items'}</div>
		</div>
	</div>
</div>