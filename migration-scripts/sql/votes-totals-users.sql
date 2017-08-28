
INSERT INTO {{usermeta}}

    (
    meta_value,
    user_id,
    meta_key
    )

SELECT

    COUNT(v.ID) `meta_value`,
    p.post_author `post_id`,
    '{{total_meta_key}}' `meta_key`

FROM

    {{votes}} v JOIN {{posts}} p
    ON v.post_id = p.ID

    GROUP BY p.post_author

;

