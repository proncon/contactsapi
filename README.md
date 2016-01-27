# contactsapi
Script to extract my Contacts and Groups from Google Contacts and perform DELETE AND PATCH


USAGE
Import Contacts and Groups from Google to REST API:
  usersgoogle.php -Import

Delete Groups from API:
  usersgoogle.php -Delete Group GROUP_ID
  GROUP_ID: the group_id of the group

Delete Contact from API:
  usersgoogle.php -Delete Contact NAME
  NAME: the NAME of the contact

Update Group on API:
  usersgoogle.php -Update Group id=ID_GROUP [name=NAME [description=DESCRIPTION] [pictureUrl=PICTUREURL]
  NAME, DESCRIPTION, PICTUREURL: optional parameters

Update Contact on API:
  usersgoogle.php -Update Contact name=NAME [phone=PHONE [pictureUrl=PICTUREURL]
  PHONE, PICTUREURL: optional parameters
