# UPGRADE FROM 3.2.0 TO 3.2.1

#### Changes regarding to product attributes:

* Support for date and datetime attributes has been added to `BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AttributeBuilder`. After the changes, date and datetime type attributes are stored in elasticsearch in the default format: date type = `Y-m-d` and datetime type = `Y-m-d H:i:s` or the format that was set in the attribute configuration in the `format` field. If in a project using this plugin you had your own implementation of the filter for date and/or datetime type attributes then you must follow the new format.
* After uploading the new version, you will have to restart the data population process with the `reset` option enabled (means simply `bin/console fos:elastic:populate`) because you have to reset the mapping beforehand in order for this field to be saved in the new format.


