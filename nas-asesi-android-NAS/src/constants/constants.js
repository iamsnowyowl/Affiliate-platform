import config from "../config/config";
import dictionary from "../config/dictionary";

const constants = {};

constants.LOGIN = "LOGIN";
constants.SIGNUP = "SIGNUP";
constants.FORGOTPASS = "FORGOTPASSWORD";
constants.LOGOUT = "LOGOUT";
constants.UPN = "UPN";

constants.SECRET_KEY = "SECRET_KEY";
constants.USERNAME_EMAIL = "USERNAME_EMAIL";
constants.FULL_NAME = "USER_FULL_NAME";

constants.SCHEMAS = "SCHEMAS";
constants.PRODUCTS = "PRODUCTS";
constants.ME = "ME";
constants.PERMISSION = "PERMISSION";

constants.ADD_ASESI = "ADD_ASESI";
constants.UPDATE_ASESI = "UPDATE_ASESI";
constants.REMOVE_ASESI = "REMOVE_ASESI";
constants.GET_ASESI = "GET_ASESI";

constants.ADD_TO_CART = "ADD_TO_CART";
constants.UPDATE_CART = "UPDATE_CART";
constants.REMOVE_FROM_CART = "REMOVE_FROM_CART";
constants.GET_FROM_CART = "GET_FROM_CART";
constants.GOTO_CART = "GOTO_CART";

constants.MULTI_LANGUANGE = "MULTI_LANGUANGE";

constants.TUK_LIST = "TUK_LIST";
constants.PORTFOLIO_UMUM = "PORTFOLIO_UMUM";
constants.PORTFOLIO_DASAR = "PORTFOLIO_DASAR";

constants.ASSESSMENTS = "ASSESSMENTS";
constants.AVAIL_ASSESSMENTS = "AVAILABLE_ASSESSMENTS";

constants.ARTICLES = "ARTICLES";

constants.PAGE_TITLE = "PAGE_TITLE";

constants.NOTIFICATION = "NOTIFICATION";

constants.FIRST_TIME = "FIRST_TIME";
constants.FCM_TOKEN = "FCM_TOKEN";

constants.URL = config.URL[config.environtment];
constants.CONTACT = config.CONTACT[config.setup]
constants.APP_NAME = "NAS Asesi";

constants.PENDIDIKAN = [
  {
    kode_pendidikan: 1,
    nama_pendidikan: "SD"
  },
  {
    kode_pendidikan: 2,
    nama_pendidikan: "SMP"
  },
  {
    kode_pendidikan: 3,
    nama_pendidikan: "SMA/Sederajat"
  },
  {
    kode_pendidikan: 10,
    nama_pendidikan: "D1"
  },
  {
    kode_pendidikan: 4,
    nama_pendidikan: "D2"
  },
  {
    kode_pendidikan: 5,
    nama_pendidikan: "D3"
  },
  {
    kode_pendidikan: 6,
    nama_pendidikan: "D4"
  },
  {
    kode_pendidikan: 7,
    nama_pendidikan: "S1"
  },
  {
    kode_pendidikan: 8,
    nama_pendidikan: "S2"
  },
  {
    kode_pendidikan: 9,
    nama_pendidikan: "S3"
  }
];

constants.AGAMA = [
  {
    nama_agama: "Islam"
  },
  {
    nama_agama: "Kristen"
  },
  {
    nama_agama: "Katolik"
  },
  {
    nama_agama: "Hindu"
  },
  {
    nama_agama: "Buddha"
  },
  {
    nama_agama: "Konghuchu"
  },
  {
    nama_agama: "Lainnya"
  }
];

constants.MULTILANGUAGE = languange => {
  return {
    hello: dictionary.lang[languange].hello,
    loading: dictionary.lang[languange].loading,
    yes: dictionary.lang[languange].yes,
    no: dictionary.lang[languange].no,
    cancel: dictionary.lang[languange].cancel,
    home: dictionary.lang[languange].home,
    product: dictionary.lang[languange].product,
    cart: dictionary.lang[languange].cart,
    profile: dictionary.lang[languange].profile,
    sorry: dictionary.lang[languange].sorry,
    not_available_now: dictionary.lang[languange].not_available_now,
    connection_lost: dictionary.lang[languange].connection_lost,

    take_a_picture: dictionary.lang[languange].take_a_picture,
    choose_from_library: dictionary.lang[languange].choose_from_library,

    your_assessment: dictionary.lang[languange].your_assessment,
    detail_assessment: dictionary.lang[languange].detail_assessment,
    empty_assessments: dictionary.lang[languange].empty_assessments,

    pra_assessment: dictionary.lang[languange].pra_assessment,
    pra_assessment_selesai: dictionary.lang[languange].pra_assessment_selesai,
    assessment: dictionary.lang[languange].assessment,
    pleno_assessment: dictionary.lang[languange].pleno_assessment,
    pleno_assessment_selesai:
      dictionary.lang[languange].pleno_assessment_selesai,
    soon: dictionary.lang[languange].soon,

    success: dictionary.lang[languange].success,
    registerred: dictionary.lang[languange].registerred,
    ok: dictionary.lang[languange].ok,
    //login
    login: dictionary.lang[languange].login,
    signup: dictionary.lang[languange].signup,
    forgotpass: dictionary.lang[languange].forgotpass,
    form_password: dictionary.lang[languange].form_password,

    //register or profile
    male: dictionary.lang[languange].male,
    female: dictionary.lang[languange].female,
    registerhint: dictionary.lang[languange].registerhint,
    first_name: dictionary.lang[languange].first_name,
    last_name: dictionary.lang[languange].last_name,
    contact: dictionary.lang[languange].contact,
    institution: dictionary.lang[languange].institution,
    job: dictionary.lang[languange].job,
    choose_tuk: dictionary.lang[languange].choose_tuk,
    register: dictionary.lang[languange].register,
    no_tuk: dictionary.lang[languange].no_tuk,
    gender: dictionary.lang[languange].gender,
    nik: dictionary.lang[languange].nik,
    npwp: dictionary.lang[languange].npwp,

    //forgotpass
    resetpasswordhint: dictionary.lang[languange].resetpasswordhint,
    send: dictionary.lang[languange].send,
    reset_password: dictionary.lang[languange].reset_password,

    //home
    hello_home: dictionary.lang[languange].hello_home,
    certificate: dictionary.lang[languange].certificate,
    quiz: dictionary.lang[languange].quiz,
    schedule: dictionary.lang[languange].schedule,
    help: dictionary.lang[languange].help,
    popular_certification: dictionary.lang[languange].popular_certification,
    new_updates: dictionary.lang[languange].new_updates,
    more: dictionary.lang[languange].more,
    browse_all: dictionary.lang[languange].browse_all,
    latest_notif: dictionary.lang[languange].latest_notif,
    notifications: dictionary.lang[languange].notifications,

    //cart
    order: dictionary.lang[languange].order,
    invoice: dictionary.lang[languange].invoice,
    history: dictionary.lang[languange].history,
    discover_certifications: dictionary.lang[languange].discover_certifications,
    empty_cart: dictionary.lang[languange].empty_cart,

    order_id: dictionary.lang[languange].order_id,
    product_name: dictionary.lang[languange].product_name,
    assessment_date: dictionary.lang[languange].assessment_date,
    total_asessee: dictionary.lang[languange].total_asessee,
    total_cost: dictionary.lang[languange].total_cost,
    persons: dictionary.lang[languange].persons,

    //profile
    setting: dictionary.lang[languange].setting,
    edit_profile: dictionary.lang[languange].edit_profile,
    select_photo: dictionary.lang[languange].select_photo,
    general_requirements: dictionary.lang[languange].general_requirements,
    basic_requirements: dictionary.lang[languange].basic_requirements,
    logout: dictionary.lang[languange].logout,
    logout_msg: dictionary.lang[languange].logout_msg,
    identity_card: dictionary.lang[languange].identity_card,
    cv: dictionary.lang[languange].cv,
    diploma_certificate: dictionary.lang[languange].diploma_certificate,
    address: dictionary.lang[languange].address,
    place_of_birth: dictionary.lang[languange].place_of_birth,
    date_of_birth: dictionary.lang[languange].date_of_birth,
    save: dictionary.lang[languange].save,
    save_dialog_title: dictionary.lang[languange].save_dialog_title,
    save_dialog_desc: dictionary.lang[languange].save_dialog_desc,
    change_password: dictionary.lang[languange].change_password,

    //certification
    validity_period: dictionary.lang[languange].validity_period,
    fee: dictionary.lang[languange].fee,
    overview: dictionary.lang[languange].overview,
    lsp_organizer: dictionary.lang[languange].lsp_organizer,
    join_certificate: dictionary.lang[languange].join_certificate,
    schedule_list: dictionary.lang[languange].schedule_list,
    choose_schedule: dictionary.lang[languange].choose_schedule,
    certification_detail: dictionary.lang[languange].certification_detail,
    choose_assessee: dictionary.lang[languange].choose_assessee,
    add_other_assessee: dictionary.lang[languange].add_other_assessee,
    add_to_cart: dictionary.lang[languange].add_to_cart,
    add_to_cart_desc: dictionary.lang[languange].add_to_cart_desc,
    add_assessee: dictionary.lang[languange].add_assessee,

    empty_product: dictionary.lang[languange].empty_product,
    empty_news: dictionary.lang[languange].empty_news,
    empty_notifications: dictionary.lang[languange].empty_notifications,

    //settings
    change_language: dictionary.lang[languange].change_language,
    indonesian: dictionary.lang[languange].indonesian,
    english: dictionary.lang[languange].english,
    term_condition: dictionary.lang[languange].term_condition,
    privacy_policies: dictionary.lang[languange].privacy_policies,
    logout: dictionary.lang[languange].logout,

    assessment_title: dictionary.lang[languange].assessment_title,
    assessment_note: dictionary.lang[languange].assessment_note,
    start_date_assessment: dictionary.lang[languange].start_date_assessment,
    assessment_address: dictionary.lang[languange].assessment_address,
    assessment_status: dictionary.lang[languange].assessment_status,

    you_registerred_in: dictionary.lang[languange].you_registerred_in,
    tuk_type: dictionary.lang[languange].tuk_type,
    oops: dictionary.lang[languange].oops,
    session_expired: dictionary.lang[languange].session_expired,

    search: dictionary.lang[languange].search,
    contact_us: dictionary.lang[languange].contact_us,

    help_title: dictionary.lang[languange].help_title,
    what_is_sertimedia: dictionary.lang[languange].what_is_sertimedia,
    what_is_certification: dictionary.lang[languange].what_is_certification,
    what_sertimedia_can_do: dictionary.lang[languange].what_sertimedia_can_do,
    why_use_Sertimedia: dictionary.lang[languange].why_use_Sertimedia,
    how_to_use_sertimedia: dictionary.lang[languange].how_to_use_sertimedia,

    show_image: dictionary.lang[languange].show_image,
    upload_image: dictionary.lang[languange].upload_image,
    reupload_image: dictionary.lang[languange].reupload_image,
    add_new_file: dictionary.lang[languange].add_new_file,

    order_review: dictionary.lang[languange].order_review,
    discount_code: dictionary.lang[languange].discount_code,
    redeem: dictionary.lang[languange].redeem,
    payment_method: dictionary.lang[languange].payment_method,
    discount: dictionary.lang[languange].discount,
    bank_transfer: dictionary.lang[languange].bank_transfer,
    proceed_payment: dictionary.lang[languange].proceed_payment,

    error_and_tryagain: dictionary.lang[languange].error_and_tryagain,
    try_again: dictionary.lang[languange].try_again,
    assessee: dictionary.lang[languange].assessee,
    unique_kode: dictionary.lang[languange].unique_kode,
    download_invoice: dictionary.lang[languange].download_invoice,
    confirm_payment: dictionary.lang[languange].confirm_payment,
    payment: dictionary.lang[languange].payment,
    ordered_at: dictionary.lang[languange].ordered_at,

    successful_transaction: dictionary.lang[languange].successful_transaction,
    transaction_detail: dictionary.lang[languange].transaction_detail,
    transaction_date: dictionary.lang[languange].transaction_date,
    detail_certification: dictionary.lang[languange].detail_certification,
    payment_information: dictionary.lang[languange].payment_information,
    location: dictionary.lang[languange].location,
    organizer: dictionary.lang[languange].organizer,
    certification_name: dictionary.lang[languange].certification_name,
    certification_date: dictionary.lang[languange].certification_date,

    title_one: dictionary.lang[languange].title_one,
    title_two: dictionary.lang[languange].title_two,
    title_three: dictionary.lang[languange].title_three,
    desc_one: dictionary.lang[languange].desc_one,
    desc_two: dictionary.lang[languange].desc_two,
    desc_three: dictionary.lang[languange].desc_three,

    next: dictionary.lang[languange].next,

    assessment2: dictionary.lang[languange].assessment2,
    assessment_desc: dictionary.lang[languange].assessment_desc,
    schedule_desc: dictionary.lang[languange].schedule_desc,
    help_desc: dictionary.lang[languange].help_desc,
    tuk_name: dictionary.lang[languange].tuk_name,
    join_assessment: dictionary.lang[languange].join_assessment,

    was_registered: dictionary.lang[languange].was_registered,
    success_join: dictionary.lang[languange].success_join,
    failed_join: dictionary.lang[languange].failed_join,
    schema_label: dictionary.lang[languange].schema_label,
    schema_desc: dictionary.lang[languange].schema_desc,
    status_recommendation: dictionary.lang[languange].status_recommendation,
    status_pleno: dictionary.lang[languange].status_pleno,

    religion: dictionary.lang[languange].religion,
    gender_code: dictionary.lang[languange].gender_code,

    desc_for_recommendation: dictionary.lang[languange].desc_for_recommendation,
    recommended: dictionary.lang[languange].recommended,
    not_recomended: dictionary.lang[languange].not_recomended,
    competent: dictionary.lang[languange].competent,
    not_competent: dictionary.lang[languange].not_competent,

    test_method: dictionary.lang[languange].test_method,
    test_competency: dictionary.lang[languange].test_competency,
    test_portfolio: dictionary.lang[languange].test_portfolio,

    check_email: dictionary.lang[languange].check_email,
    cannot_empty: dictionary.lang[languange].cannot_empty,
    cannot_space: dictionary.lang[languange].cannot_space,
    not_registerred_as_assessee:
      dictionary.lang[languange].not_registerred_as_assessee,
    cannot_have_account: dictionary.lang[languange].cannot_have_account,
    regist_here: dictionary.lang[languange].regist_here,
    failed: dictionary.lang[languange].failed,
    size_too_large: dictionary.lang[languange].size_too_large,
    assessor_name: dictionary.lang[languange].assessor_name,
    pendidikan_terakhir: dictionary.lang[languange].pendidikan_terakhir,

    save_sign: dictionary.lang[languange].save_sign,
    clear_sign: dictionary.lang[languange].clear_sign,
    sign_canvas: dictionary.lang[languange].sign_canvas,

    schema_name: dictionary.lang[languange].schema_name,
    schema_number: dictionary.lang[languange].schema_number,
    sub_schema_name: dictionary.lang[languange].sub_schema_name,
    competence_unit: dictionary.lang[languange].competence_unit,
    request_schema: dictionary.lang[languange].request_schema,
    request: dictionary.lang[languange].request,
    request_dialog_title: dictionary.lang[languange].request_dialog_title,
    request_dialog_desc: dictionary.lang[languange].request_dialog_desc,
    kebangsaan: dictionary.lang[languange].kebangsaan,

    publish_certificate: dictionary.lang[languange].publish_certificate,
    assessment_done: dictionary.lang[languange].assessment_done,
    type_submission: dictionary.lang[languange].type_submission,
    file_requirement: dictionary.lang[languange].file_requirement,
    new_certification: dictionary.lang[languange].new_certification,
    recertification: dictionary.lang[languange].recertification,
    complete_your_data: dictionary.lang[languange].complete_your_data
  };
};

export default constants;
