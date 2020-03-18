const permisiion = {};

permisiion.role = {
  DEV: {
    en: {
      items: [
        {
          name: "Home",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Users",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-note"
        },
        {
          name: "Assessors",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Participant",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Schema Certification",
          icon: "fa icon-grid",
          children: [
            {
              name: "Main Schema",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Schema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Competention",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Assessment",
          icon: "icon-calendar",
          children: [
            {
              name: "Submission",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Rejected",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "List Assessment",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Archives",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Portfolio",
          icon: "fa icon-layers",
          url: "/portfolios"
        },
        {
          name: "Data Base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        },
        {
          name: "Management Letter",
          url: "/management-letters",
          icon: "icon-folder-alt"
        },
        {
          name: "Log Data",
          url: "/restore-data",
          icon: "icon-trash"
        }
      ]
    },
    id: {
      items: [
        {
          name: "Beranda",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Pengguna",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-pencil"
        },
        {
          name: "Asesor",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Peserta",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Skema Sertifikasi",
          icon: "fa icon-grid",
          children: [
            {
              name: "Skema Utama",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Skema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Kompetensi",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Asesmen",
          icon: "icon-calendar",
          children: [
            {
              name: "Pengajuan",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Dibatalkan",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "Daftar Asesmen",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Arsip",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Portofolio",
          url: "/portfolios",
          icon: "fa icon-layers"
        },
        {
          name: "Data Base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        },
        {
          name: "Pengelolaan Surat",
          url: "/management-letters",
          icon: "icon-folder-alt"
        },
        {
          name: "Log Data",
          url: "/restore-data",
          icon: "icon-trash"
        }
      ]
    }
  },
  MAG: {
    en: {
      items: [
        {
          name: "Home",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Users",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-note"
        },
        {
          name: "Assessors",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Participant",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Schema Certification",
          icon: "fa icon-grid",
          children: [
            {
              name: "Main Schema",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Schema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Competention",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Assessment",
          icon: "icon-calendar",
          children: [
            {
              name: "Submission",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Rejected",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "List Assessment",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Archives",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Portfolio",
          icon: "fa icon-layers",
          url: "/portfolios"
        },
        {
          name: "Data Base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        },
        {
          name: "Log Data",
          url: "/restore-data",
          icon: "icon-trash"
        }
      ]
    },
    id: {
      items: [
        {
          name: "Beranda",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Pengguna",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-pencil"
        },
        {
          name: "Asesor",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Peserta",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Skema Sertifikasi",
          icon: "fa icon-grid",
          children: [
            {
              name: "Skema Utama",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Skema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Kompetensi",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Asesmen",
          icon: "icon-calendar",
          children: [
            {
              name: "Pengajuan",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Dibatalkan",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "Daftar Asesmen",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Arsip",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Portofolio",
          url: "/portfolios",
          icon: "fa icon-layers"
        },
        {
          name: "Data Base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        },
        {
          name: "Log Data",
          url: "/restore-data",
          icon: "icon-trash"
        }
      ]
    }
  },
  SUP: {
    en: {
      items: [
        {
          name: "Home",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Users",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-note"
        },
        {
          name: "Assessors",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Participant",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Schema Certification",
          icon: "fa icon-grid",
          children: [
            {
              name: "Main Schema",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Schema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Competention",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Assessment",
          icon: "icon-calendar",
          children: [
            {
              name: "Submission",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Rejected",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "List Assessment",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Archives",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Portfolio",
          icon: "fa icon-layers",
          url: "/portfolios"
        },
        {
          name: "Data Base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        },
        {
          name: "Log Data",
          url: "/restore-data",
          icon: "icon-trash"
        }
      ]
    },
    id: {
      items: [
        {
          name: "Beranda",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Pengguna",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-pencil"
        },
        {
          name: "Asesor",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Peserta",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Skema Sertifikasi",
          icon: "fa icon-grid",
          children: [
            {
              name: "Skema Utama",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Skema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Kompetensi",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Asesmen",
          icon: "icon-calendar",
          children: [
            {
              name: "Pengajuan",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Dibatalkan",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "Daftar Asesmen",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Arsip",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Portofolio",
          url: "/portfolios",
          icon: "fa icon-layers"
        },
        {
          name: "Data Base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        },
        {
          name: "Log Data",
          url: "/restore-data",
          icon: "icon-trash"
        }
      ]
    }
  },
  ADM: {
    en: {
      items: [
        {
          name: "Home",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Users",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-note"
        },
        {
          name: "Assessors",
          url: "/Assessors",
          icon: "fa fa-user-circle-o"
        },
        {
          name: "Participant",
          url: "/asesi",
          icon: "fa fa-user-circle-o"
        },
        {
          name: "Schema Certification",
          icon: "fa icon-grid",
          children: [
            {
              name: "Main Schema",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Schema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Competention",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Assessment",
          icon: "icon-calendar",
          children: [
            {
              name: "Submission",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Rejected",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "List Assessment",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Archives",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Data base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        }
      ]
    },
    id: {
      items: [
        {
          name: "Beranda",
          url: "/dashboard",
          icon: "icon-home"
        },
        {
          name: "Pengguna",
          url: "/users",
          icon: "icon-people"
        },
        {
          name: "TUK",
          url: "/tuk",
          icon: "icon-pencil"
        },
        {
          name: "Asesor",
          url: "/Assessors",
          icon: "icon-user"
        },
        {
          name: "Peserta",
          url: "/asesi",
          icon: "icon-note"
        },
        {
          name: "Skema Sertifikasi",
          icon: "fa icon-grid",
          children: [
            {
              name: "Skema Utama",
              url: "/schema/main-schema",
              icon: "fa icon-grid"
            },
            {
              name: "Sub Skema",
              url: "/schema/sub-schema",
              icon: "fa fa-th"
            },
            // {
            //   name: "Unit Kompetensi",
            //   url: "/schema/unit-competention",
            //   icon: "fa fa-th"
            // }
          ]
        },
        {
          name: "Asesmen",
          icon: "icon-calendar",
          children: [
            {
              name: "Pengajuan",
              url: "/assessments/submission",
              icon: "icon-calendar"
            },
            {
              name: "Dibatalkan",
              url: "/assessments/rejected",
              icon: "icon-calendar"
            },
            {
              name: "Daftar Asesmen",
              url: "/assessments/list",
              icon: "icon-calendar"
            },
            {
              name: "Arsip",
              url: "/assessments/archives",
              icon: "icon-calendar"
            }
          ]
        },
        {
          name: "Data base Asesi",
          url: "/alumnis",
          icon: "icon-badge"
        }
      ]
    }
  },
  ACS: {
    en: {
      items: [
        {
          name: "Home",
          url: "/dashboard",
          icon: "icon-calendar"
        }
      ]
    },
    id: {
      items: [
        {
          name: "Beranda",
          url: "/dashboard",
          icon: "icon-calendar"
        }
      ]
    }
  },
  APL: {
    en: {
      items: [
        {
          name: "Home",
          url: "/dashboard",
          icon: "icon-calendar"
        }
      ]
    },
    id: {
      items: [
        {
          name: "Beranda",
          url: "/dashboard",
          icon: "icon-calendar"
        }
      ]
    }
  }
};

export const Permission = (bahasa, role) => {
  return permisiion.role[role][bahasa];
};
