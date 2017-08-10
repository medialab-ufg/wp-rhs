INSERT INTO {{postmeta}}(

    post_id,
    meta_value,
    meta_key
)

SELECT
   ID as `post_id`,
   '1' as `meta_value`,
   '{{meta_publish_key}}' as `meta_key`

FROM

    {{posts}} p

    WHERE p.post_status = 'publish';
