-- DROP SCHEMA public;

CREATE SCHEMA public AUTHORIZATION postgres;

-- DROP SEQUENCE cart_item_id_seq;

CREATE SEQUENCE cart_item_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE failed_jobs_id_seq;

CREATE SEQUENCE failed_jobs_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE migrations_id_seq;

CREATE SEQUENCE migrations_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 2147483647
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE order_confirmation_id_seq;

CREATE SEQUENCE order_confirmation_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE order_id_seq;

CREATE SEQUENCE order_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE order_item_id_seq;

CREATE SEQUENCE order_item_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE personal_access_tokens_id_seq;

CREATE SEQUENCE personal_access_tokens_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE product_category_id_seq;

CREATE SEQUENCE product_category_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE product_id_seq;

CREATE SEQUENCE product_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE product_images_id_seq;

CREATE SEQUENCE product_images_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE role_id_seq;

CREATE SEQUENCE role_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE sell_out_id_seq;

CREATE SEQUENCE sell_out_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE shopping_session_id_seq;

CREATE SEQUENCE shopping_session_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE user_address_id_seq;

CREATE SEQUENCE user_address_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE user_id_seq;

CREATE SEQUENCE user_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE user_role_id_seq;

CREATE SEQUENCE user_role_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;
-- DROP SEQUENCE users_id_seq;

CREATE SEQUENCE users_id_seq
	INCREMENT BY 1
	MINVALUE 1
	MAXVALUE 9223372036854775807
	START 1
	CACHE 1
	NO CYCLE;-- public.failed_jobs definition

-- Drop table

-- DROP TABLE failed_jobs;

CREATE TABLE failed_jobs (
	id bigserial NOT NULL,
	"uuid" varchar(255) NOT NULL,
	"connection" text NOT NULL,
	queue text NOT NULL,
	payload text NOT NULL,
	"exception" text NOT NULL,
	failed_at timestamp(0) DEFAULT CURRENT_TIMESTAMP NOT NULL,
	CONSTRAINT failed_jobs_pkey PRIMARY KEY (id),
	CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid)
);


-- public.migrations definition

-- Drop table

-- DROP TABLE migrations;

CREATE TABLE migrations (
	id serial4 NOT NULL,
	migration varchar(255) NOT NULL,
	batch int4 NOT NULL,
	CONSTRAINT migrations_pkey PRIMARY KEY (id)
);


-- public.password_reset_tokens definition

-- Drop table

-- DROP TABLE password_reset_tokens;

CREATE TABLE password_reset_tokens (
	email varchar(255) NOT NULL,
	"token" varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email)
);


-- public.personal_access_tokens definition

-- Drop table

-- DROP TABLE personal_access_tokens;

CREATE TABLE personal_access_tokens (
	id bigserial NOT NULL,
	tokenable_type varchar(255) NOT NULL,
	tokenable_id int8 NOT NULL,
	"name" varchar(255) NOT NULL,
	"token" varchar(64) NOT NULL,
	abilities text NULL,
	last_used_at timestamp(0) NULL,
	expires_at timestamp(0) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id),
	CONSTRAINT personal_access_tokens_token_unique UNIQUE (token)
);
CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


-- public.product_category definition

-- Drop table

-- DROP TABLE product_category;

CREATE TABLE product_category (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	"name" varchar NOT NULL,
	"desc" varchar NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	CONSTRAINT product_category_pkey PRIMARY KEY (id)
);


-- public."role" definition

-- Drop table

-- DROP TABLE "role";

CREATE TABLE "role" (
	id bigserial NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	role_name varchar(255) DEFAULT 'user'::character varying NOT NULL,
	CONSTRAINT role_pkey PRIMARY KEY (id),
	CONSTRAINT role_role_name_check CHECK (((role_name)::text = ANY ((ARRAY['admin'::character varying, 'user'::character varying])::text[]))),
	CONSTRAINT role_role_name_unique UNIQUE (role_name)
);


-- public.sell_out definition

-- Drop table

-- DROP TABLE sell_out;

CREATE TABLE sell_out (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	product_id int8 NOT NULL,
	sell_out int8 DEFAULT '0'::bigint NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	CONSTRAINT sell_out_pkey PRIMARY KEY (id)
);


-- public."user" definition

-- Drop table

-- DROP TABLE "user";

CREATE TABLE "user" (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	"uuid" uuid DEFAULT auth.uid() NOT NULL,
	"name" varchar NOT NULL,
	email varchar NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NULL,
	CONSTRAINT user_pkey PRIMARY KEY (uuid),
	CONSTRAINT user_uuid_key UNIQUE (uuid)
);


-- public.users definition

-- Drop table

-- DROP TABLE users;

CREATE TABLE users (
	id bigserial NOT NULL,
	"name" varchar(255) NOT NULL,
	email varchar(255) NOT NULL,
	email_verified_at timestamp(0) NULL,
	"password" varchar(255) NOT NULL,
	remember_token varchar(100) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT users_email_unique UNIQUE (email),
	CONSTRAINT users_pkey PRIMARY KEY (id)
);


-- public."order" definition

-- Drop table

-- DROP TABLE "order";

CREATE TABLE "order" (

);


-- public.product definition

-- Drop table

-- DROP TABLE product;

CREATE TABLE product (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	category_id int8 NULL,
	"SKU" varchar NOT NULL,
	"name" varchar NOT NULL,
	"desc" varchar NOT NULL,
	price int8 NOT NULL,
	discount float8 NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	qty int8 DEFAULT '0'::bigint NOT NULL,
	sell_out int8 DEFAULT '0'::bigint NOT NULL,
	weight int8 DEFAULT '100'::bigint NOT NULL,
	CONSTRAINT product_pkey PRIMARY KEY (id),
	CONSTRAINT public_product_category_id_fkey FOREIGN KEY (category_id) REFERENCES product_category(id)
);


-- public.product_image definition

-- Drop table

-- DROP TABLE product_image;

CREATE TABLE product_image (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	product_id int8 NOT NULL,
	image_url varchar NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	"name" varchar(255) DEFAULT 'supabase'::character varying NOT NULL,
	"path" varchar(255) DEFAULT 'supabase/folder'::character varying NOT NULL,
	CONSTRAINT product_images_pkey PRIMARY KEY (id),
	CONSTRAINT public_product_images_product_id_fkey FOREIGN KEY (product_id) REFERENCES product(id)
);


-- public.shopping_session definition

-- Drop table

-- DROP TABLE shopping_session;

CREATE TABLE shopping_session (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	total_payment float8 DEFAULT '0'::double precision NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	user_uuid uuid DEFAULT auth.uid() NOT NULL,
	sub_total float8 DEFAULT '0'::double precision NULL,
	is_done int2 DEFAULT '0'::smallint NOT NULL,
	CONSTRAINT shopping_session_pkey PRIMARY KEY (id),
	CONSTRAINT shopping_session_user_uuid_fkey FOREIGN KEY (user_uuid) REFERENCES "user"("uuid")
);


-- public.user_address definition

-- Drop table

-- DROP TABLE user_address;

CREATE TABLE user_address (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	whatsapp_number numeric NULL,
	province varchar NULL,
	city varchar NULL,
	district varchar NULL,
	additional_address varchar NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	postal_code int4 NULL,
	user_uuid uuid DEFAULT auth.uid() NULL,
	city_id int8 NOT NULL,
	province_id int8 NOT NULL,
	CONSTRAINT user_address_pkey PRIMARY KEY (id),
	CONSTRAINT user_address_user_uuid_fkey FOREIGN KEY (user_uuid) REFERENCES "user"("uuid")
);


-- public.user_role definition

-- Drop table

-- DROP TABLE user_role;

CREATE TABLE user_role (
	id bigserial NOT NULL,
	role_id int8 NOT NULL,
	users_id int8 NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT user_role_pkey PRIMARY KEY (id),
	CONSTRAINT user_role_role_id_foreign FOREIGN KEY (role_id) REFERENCES "role"(id),
	CONSTRAINT user_role_users_id_foreign FOREIGN KEY (users_id) REFERENCES users(id)
);


-- public.cart_item definition

-- Drop table

-- DROP TABLE cart_item;

CREATE TABLE cart_item (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	session_id int8 NOT NULL,
	product_id int8 NOT NULL,
	qty int4 NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	image_url text NULL,
	CONSTRAINT cart_item_pkey PRIMARY KEY (id),
	CONSTRAINT cart_item_session_id_fkey FOREIGN KEY (session_id) REFERENCES shopping_session(id),
	CONSTRAINT public_cart_item_product_id_fkey FOREIGN KEY (product_id) REFERENCES product(id)
);


-- public."order" definition

-- Drop table

-- DROP TABLE "order";

CREATE TABLE "order" (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	shopping_session_id int8 NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	name_receiver varchar NULL,
	detail_address varchar NULL,
	city varchar NULL,
	province varchar NULL,
	city_id int8 NULL,
	province_id int8 NULL,
	midtrans_token text NULL,
	midtrans_id text NULL,
	total_payment int8 DEFAULT '0'::bigint NOT NULL,
	shipping_price int8 NULL,
	sub_total int8 NULL,
	status varchar(255) DEFAULT 'pending'::character varying NOT NULL,
	CONSTRAINT order_pkey PRIMARY KEY (id),
	CONSTRAINT order_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'paid'::character varying, 'send'::character varying, 'need_confirmation'::character varying, 'done'::character varying, 'cancel'::character varying])::text[]))),
	CONSTRAINT order_shopping_session_id_fkey FOREIGN KEY (shopping_session_id) REFERENCES shopping_session(id)
);


-- public.order_confirmation definition

-- Drop table

-- DROP TABLE order_confirmation;

CREATE TABLE order_confirmation (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	order_id int8 NULL,
	is_confirmed int2 NULL,
	created_at timestamptz DEFAULT timezone('utc'::text, now()) NOT NULL,
	modified_at timestamptz DEFAULT timezone('utc'::text, now()) NULL,
	status varchar(255) DEFAULT 'pending'::character varying NOT NULL,
	CONSTRAINT order_confirmation_pkey PRIMARY KEY (id),
	CONSTRAINT order_confirmation_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'paid'::character varying, 'send'::character varying, 'need_confirmation'::character varying, 'cancel'::character varying])::text[]))),
	CONSTRAINT order_confirmation_order_id_fkey FOREIGN KEY (order_id) REFERENCES "order"(id)
);


-- public.order_item definition

-- Drop table

-- DROP TABLE order_item;

CREATE TABLE order_item (
	id int8 GENERATED BY DEFAULT AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	order_id int8 NOT NULL,
	product_id int8 NOT NULL,
	quantity int8 NOT NULL,
	created_at timestamptz DEFAULT now() NOT NULL,
	modified_at timestamptz DEFAULT now() NOT NULL,
	product_name text NULL,
	price int8 NULL,
	image_url text NULL,
	shipping_price text NULL,
	CONSTRAINT order_item_pkey PRIMARY KEY (id),
	CONSTRAINT order_item_order_id_fkey FOREIGN KEY (order_id) REFERENCES "order"(id),
	CONSTRAINT public_order_item_product_id_fkey FOREIGN KEY (product_id) REFERENCES product(id)
);