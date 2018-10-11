SELECT fu.fid, fu.id, f.filename, f.filepath
  FROM {{drupaldb}}.file_usage AS fu join {{drupaldb}}.files AS f
ON fu.fid = f.fid;