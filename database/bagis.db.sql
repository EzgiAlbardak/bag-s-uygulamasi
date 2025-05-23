PGDMP  $    2                }            bagis_db    15.12    17.4     
           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false                       1262    16410    bagis_db    DATABASE     ~   CREATE DATABASE bagis_db WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Turkish_T� rkiye.1254';
    DROP DATABASE bagis_db;
                     postgres    false            �            1259    16458    donation    TABLE     �   CREATE TABLE public.donation (
    id integer NOT NULL,
    amount double precision NOT NULL,
    description character varying(255),
    created_at timestamp without time zone,
    user_id integer NOT NULL
);
    DROP TABLE public.donation;
       public         heap r       postgres    false            �            1259    16457    donation_id_seq    SEQUENCE     �   CREATE SEQUENCE public.donation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.donation_id_seq;
       public               postgres    false    217                       0    0    donation_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.donation_id_seq OWNED BY public.donation.id;
          public               postgres    false    216            �            1259    16435    user    TABLE     �   CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(80) NOT NULL,
    email character varying(120) NOT NULL,
    password_hash character varying(256) NOT NULL,
    is_admin boolean DEFAULT false
);
    DROP TABLE public."user";
       public         heap r       postgres    false                       0    0    COLUMN "user".is_admin    COMMENT     8   COMMENT ON COLUMN public."user".is_admin IS 'is_admin';
          public               postgres    false    215            �            1259    16434    user_id_seq    SEQUENCE     �   CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.user_id_seq;
       public               postgres    false    215                       0    0    user_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;
          public               postgres    false    214            l           2604    16461    donation id    DEFAULT     j   ALTER TABLE ONLY public.donation ALTER COLUMN id SET DEFAULT nextval('public.donation_id_seq'::regclass);
 :   ALTER TABLE public.donation ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    217    216    217            j           2604    16438    user id    DEFAULT     d   ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);
 8   ALTER TABLE public."user" ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    215    214    215                      0    16458    donation 
   TABLE DATA           P   COPY public.donation (id, amount, description, created_at, user_id) FROM stdin;
    public               postgres    false    217   �                 0    16435    user 
   TABLE DATA           N   COPY public."user" (id, username, email, password_hash, is_admin) FROM stdin;
    public               postgres    false    215   �                  0    0    donation_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.donation_id_seq', 9, true);
          public               postgres    false    216                       0    0    user_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.user_id_seq', 19, true);
          public               postgres    false    214            t           2606    16463    donation donation_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.donation
    ADD CONSTRAINT donation_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.donation DROP CONSTRAINT donation_pkey;
       public                 postgres    false    217            n           2606    16444    user user_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public."user" DROP CONSTRAINT user_email_key;
       public                 postgres    false    215            p           2606    16440    user user_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public."user" DROP CONSTRAINT user_pkey;
       public                 postgres    false    215            r           2606    16442    user user_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public."user" DROP CONSTRAINT user_username_key;
       public                 postgres    false    215            u           2606    16464    donation donation_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.donation
    ADD CONSTRAINT donation_user_id_fkey FOREIGN KEY (user_id) REFERENCES public."user"(id) ON DELETE CASCADE;
 H   ALTER TABLE ONLY public.donation DROP CONSTRAINT donation_user_id_fkey;
       public               postgres    false    215    217    3184               �   x�u�=N1��z|
_ ��x�񸣦�B�1b��B�P�ђ3��N��"\?��֏����>���-w�q����ӆ�#V)U0db-�E��pq?��YP�<����G��涛�����}l"jP\��3;1���X�Ơ.C�._�����CN#�4�d�G��
��0yʕ�JA掩��K~��=����S��o�P����?��叶ʩ����n�s�$W         �  x�uU�n7<��"�=d����I�X	�H�e=� A�l�+iW�vW��3����c+� �\恙aUuUgc�p��߯��f���۵']��ڛv��������m���-���������?�N�7�./�N�8*h@��Fu@EI	;� (���-��c��!�X�0&��T����z2�0�b�ԇ�
�,L��!��=�8;���iw�^����]�^^h*���/��?���2IM�������N��F+)����^����ARZȁGjZ	1�B�t��רb���X�4�Z8Bb���������f�Y�����-?m�������w�󑐻�1��L1&�kIZ� tud57r.������Upl�K�Q3� Rr�G�\k����	28�\Z�����h���٩�7?���b5[��v���b�L^�:�����馬��]��@&��3�8akqĜI3G��8�P@��(�J��jG�,����\U�R;�`��Ӓ�,�����du��8S�J$9������l�����/w�߮_����������Ӈ�����s�AA�R��&��nԱ��br	20�)���v�=�TG"��gUp�n���F�1���p�*1�;�Pj��*xP&�7
'�ÿo�C���wg�������yN��T��Ԭl�Z���k'Nj�b�� 7�����
��f���h5J��r��WT�^���'��T*�O����Z��M�i����Zuv�u�������Ž���f}~z5�L�(� ,�,){�D�K�s���NO[�0WO@��ÓK�4�.�36��H�	@��#�(P�x:t���-��}XZ�ag��y�m��鮾��������v���1�|����|��aK�A �:�!�"���p��G=ܻ�z�C�DQ�������mm�ػ�כ���������V�����g?��2���<�8�p����>:���qv8��v�HS����$����9�^�H\���rǜ؁�ZS�V���J!!��̀��=�5�L߫%��8�`��j�J����Ɖ�������Y������~���B�7���w�xttV~�Ƨ�4ᬥzN�}K�ά�h������P��t����v���C%���Q��r[�H:*D�oe�%�{(����7�[�s�<hCB�8��~{�����*]     