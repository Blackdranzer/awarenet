<? /*
<form name='editSection' method='POST' action='%%serverPath%%projects/savesection/'>
<input type='hidden' name='action' value='saveSection' />
<input type='hidden' name='UID' value='%%projectUID%%' />
<input type='hidden' name='sectionUID' value='%%sectionUID%%' />

<h1>%%projectTitle%% (%%sectionTitle%%)</h1>

<table noborder>
  <tr>
    <td><b>Title:</b></td>
	<td><input type='text' name='sectionTitle' value='%%sectionTitle%%' size='55'/></td>
  </tr>
</table>

%%contentJs64%%
[[:editor::base64::jsvar=contentJs64::name=content:]]
<br/>

<table noborder>
  <tr>
    <td valign='top'>
     <input type='submit' value='Save Changes' />
     </form>
    </td>
    <td>
    <form name='cancelEdit' method='GET' action='%%editUrl%%'>
    <input type='submit' value='Cancel' />
    </form>
    </td>
    <td>
    <form name='deleteSection' method='GET' 
          action='%%serverPath%%projects/confirmdeletesection/UID_%%projectUID%%/section_%%sectionUID%%/' >
      <input type='submit' value='Delete This Section' />
    <form>
    </td>
 </tr>
</table>
<br/>

[[:theme::navtitlebox::label=Images::toggle=divEditImages:]]
<div id='divEditImages'>
[[:images::uploadmultiple::refModule=projects::refModel=projects_project::refUID=%%projectUID%%:]]
</div>
<br/>
*/ ?>
