-- Drop Table fbUsers;
CREATE TABLE fbUsers
(
	user_id serial NOT NULL primary key,
	first_name character varying(300) NOT NULL,
	last_name character varying(300) ,
	user_hometown  character varying(300) ,
	user_email character varying(320) NOT NULL,
	fb_user_id  character varying(320) NOT NULL,
	password character varying(300) ,
	login_time timestamp with time zone NOT NULL DEFAULT now(),
	registration_time timestamp with time zone NOT NULL DEFAULT now()
);
 
COMMENT ON Table users IS 'Tabel store the new users registration';

CREATE INDEX  fb_user_id_idx
  ON  fbUsers
  USING btree
  (fb_user_id);

CREATE INDEX  password_idx
  ON  fbUsers
  USING btree
  (password);

