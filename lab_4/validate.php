<?php

function validateFirstName($value) {
    $v = trim($value ?? '');
    if ($v === '') return 'First Name is required.';
    if (preg_match('/[0-9]/', $v)) return 'First Name cannot contain numbers.';
    if (!preg_match('/^[\p{L}\s\-\.\']+$/u', $v)) return 'First Name should contain only letters and spaces.';
    return null;
}

function validateLastName($value) {
    $v = trim($value ?? '');
    if ($v === '') return 'Last Name is required.';
    if (preg_match('/[0-9]/', $v)) return 'Last Name cannot contain numbers.';
    if (!preg_match('/^[\p{L}\s\-\.\']+$/u', $v)) return 'Last Name should contain only letters and spaces.';
    return null;
}

function validateAddress($value) {
    if (trim($value ?? '') === '') return 'Address is required.';
    return null;
}

function validateCountry($value) {
    if (trim($value ?? '') === '') return 'Country is required.';
    return null;
}

function validateGender($value) {
    if (trim($value ?? '') === '') return 'Gender is required.';
    if (!in_array(trim($value), ['Male', 'Female'], true)) return 'Please select a valid gender.';
    return null;
}

function validateSkills($arr) {
    if (!is_array($arr)) return 'At least one Skill must be selected.';
    $arr = array_filter(array_map('trim', $arr));
    if (count($arr) === 0) return 'At least one Skill must be selected.';
    return null;
}

function validateUsername($value) {
    $v = trim($value ?? '');
    if ($v === '') return 'Username is required.';
    return null;
}

function validatePassword($value) {
    $v = $value ?? '';
    if ($v === '') return 'Password is required.';
    if (strlen($v) !== 8) return 'Password must be exactly 8 characters.';
    if (preg_match('/[A-Z]/', $v)) return 'Password cannot contain capital letters.';
    if (preg_match('/[^a-z0-9_]/', $v)) return 'Password may only contain lowercase letters, numbers, and underscore.';
    return null;
}

function validateProfilePicture($file) {
    if (empty($file['name'])) return null; // optional
    if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) return 'Profile picture upload failed.';
    $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed, true)) return 'Profile picture must be JPG or PNG only.';
    $maxBytes = 2 * 1024 * 1024;
    if ($file['size'] > $maxBytes) return 'Profile picture must be less than 2 MB.';
    return null;
}
