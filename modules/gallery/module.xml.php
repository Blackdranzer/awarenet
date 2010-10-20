<? /*
<module>
    <modulename>gallery</modulename>
    <version>1.0</version>
    <revision>0</revision>
    <description>User image galleries.</description>
    <core>no</core>
    <installed>no</installed>
    <enabled>yes</enabled>
    <dbschema></dbschema>
    <search>no</search>
	<dependencies>
	</dependencies>
    <models>
      <model>
        <name>Gallery_Gallery</name>
		<description>User picture gallery.</description>
        <permissions>
          <permission>new</permission>
          <permission>show</permission>
          <permission>edit</permission>
          <permission>delete</permission>
        </permissions>
        <relationships>
		  <relationship>creator</relationship>
		  <relationship>owner</relationship>
        </relationships>
      </model>
    </models>
    <defaultpermissions>
		student:p|gallery|Gallery_Gallery|new
		student:p|gallery|Gallery_Gallery|show
		student:p|gallery|Gallery_Gallery|images-show
		student:p|gallery|Gallery_Gallery|comments-add
		student:p|gallery|Gallery_Gallery|comments-show
		student:c|gallery|Gallery_Gallery|edit|(if)|creator
		student:c|gallery|Gallery_Gallery|images-add|(if)|creator
		student:c|gallery|Gallery_Gallery|images-remove|(if)|creator
		student:c|gallery|Gallery_Gallery|images-edit|(if)|creator
		student:c|gallery|Gallery_Gallery|delete|(if)|creator

		teacher:p|gallery|Gallery_Gallery|new
		teacher:p|gallery|Gallery_Gallery|show
		teacher:p|gallery|Gallery_Gallery|images-show
		student:p|gallery|Gallery_Gallery|comments-add
		student:p|gallery|Gallery_Gallery|comments-show
		teacher:c|gallery|Gallery_Gallery|edit|(if)|creator
		teacher:c|gallery|Gallery_Gallery|images-add|(if)|creator
		teacher:c|gallery|Gallery_Gallery|images-remove|(if)|creator
		teacher:c|gallery|Gallery_Gallery|images-edit|(if)|creator
		teacher:c|gallery|Gallery_Gallery|delete|(if)|creator
    </defaultpermissions></module>
*/ ?>
