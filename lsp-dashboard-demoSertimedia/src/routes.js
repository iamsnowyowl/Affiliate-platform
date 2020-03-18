import React from "react";
import Loadable from "react-loadable";
import DefaultLayout from "./containers/DefaultLayout/DefaultLayout";
import "../src/css/loaderComponent.css";
import { multiLanguage } from "./components/Language/getBahasa";

function Loading() {
  return (
    <div id="preloader">
      <div id="loader" />
    </div>
  );
}

const Assessors = Loadable({
  loader: () => import("./views/Asesor/Asesors"),
  loading: Loading
});

const Applicant = Loadable({
  loader: () => import("./views/Applicant/Applicant"),
  loading: Loading
});

const Dashboard = Loadable({
  loader: () => import("./views/Dashboard/Dashboard"),
  loading: Loading
});

const SubSchema = Loadable({
  loader: () => import("./views/Competences/SubSchema/SubSchema"),
  loading: Loading
});

const EditAssessors = Loadable({
  loader: () => import("./components/EditData/EditData_acceccors"),
  loading: Loading
});

const EditApplicant = Loadable({
  loader: () => import("./components/EditData/EditData_applicant"),
  loading: Loading
});

const EditData = Loadable({
  loader: () => import("./components/EditData/EditData"),
  loading: Loading
});

const EditData_subSchema = Loadable({
  loader: () => import("./components/EditData/EditData_subSchema"),
  loading: Loading
});

const EditData_mainSchema = Loadable({
  loader: () => import("./components/EditData/EditData_mainSchema"),
  loading: Loading
});

const EditTuk = Loadable({
  loader: () => import("./components/EditData/EditData_tuk"),
  loading: Loading
});

const MainSchema = Loadable({
  loader: () => import("./views/Competences/MainSchema/MainSchema"),
  loading: Loading
});

const InputData = Loadable({
  loader: () => import("./components/InputData/InputData"),
  loading: Loading
});

const InputData_subSchema = Loadable({
  loader: () => import("./components/InputData/InputData_subSchema"),
  loading: Loading
});

const InputData_mainSchema = Loadable({
  loader: () => import("./components/InputData/InputData_mainSchema"),
  loading: Loading
});

const InputData_tuk = Loadable({
  loader: () => import("./components/InputData/InputData_tuk"),
  loading: Loading
});

const ListSkill = Loadable({
  loader: () => import("./views/Asesor/ListSkill"),
  loading: Loading
});

const PendingCompetance = Loadable({
  loader: () => import("./views/Asesor/PendingCompetance"),
  loading: Loading
});

const Schedule = Loadable({
  loader: () => import("./views/Schedule/Schedule"),
  loading: Loading
});

const Submissions = Loadable({
  loader: () => import("./views/Schedule/Submissions"),
  loading: Loading
});

const InputAssessment = Loadable({
  loader: () => import("./components/InputData/InputData_Assessment"),
  loading: Loading
});

const Schedule_accessors = Loadable({
  loader: () => import("./views/Schedule/Schedule_accessors"),
  loading: Loading
});

const TUK = Loadable({
  loader: () => import("./views/TUK/TUK"),
  loading: Loading
});

const Users = Loadable({
  loader: () => import("./views/Users/Users"),
  loading: Loading
});

const NotFound = Loadable({
  loader: () => import("./views/Pages/Page404/Page404"),
  loading: Loading
});

const News = Loadable({
  loader: () => import("./views/Pages/News/NewsWebViews"),
  loading: Loading
});

const AssignApplicant = Loadable({
  loader: () => import("./views/Schedule/AssignApplicant"),
  loading: Loading
});

const AssignAssessors = Loadable({
  loader: () => import("./views/Schedule/AssignAssessors"),
  loading: Loading
});

const AssignAdmin = Loadable({
  loader: () => import("./views/Schedule/AssignAdmin"),
  loading: Loading
});

const Assign = Loadable({
  loader: () => import("./components/Detail/Detail.js"),
  loading: Loading
});

const Portofolio = Loadable({
  loader: () => import("./components/Detail/Portofolio"),
  loading: Loading
});

const Notif = Loadable({
  loader: () => import("./components/Notification/Notification"),
  loading: Loading
});

const AssignPleno = Loadable({
  loader: () => import("./components/InputData/InputData_Plenos"),
  loading: Loading
});

const GenerateLetters = Loadable({
  loader: () => import("./views/Correspondence/Letters"),
  loading: Loading
});

const Alumni = Loadable({
  loader: () => import("./views/Alumni/Alumni"),
  loading: Loading
});

const InputAlumni = Loadable({
  loader: () => import("./components/InputData/InputData_alumni"),
  loading: Loading
});

const EditAlumni = Loadable({
  loader: () => import("./components/EditData/EditData_alumni"),
  loading: Loading
});

const Portfolios = Loadable({
  loader: () => import("./views/MasterData/Portfolios"),
  loading: Loading
});

const InputData_Portfolios = Loadable({
  loader: () => import("./components/InputData/InputData_portofolio"),
  loading: Loading
});

const EditData_portfoliosMaster = Loadable({
  loader: () => import("./components/EditData/EditData_portfoliosMaster"),
  loading: Loading
});

const DetailAssessment = Loadable({
  loader: () => import("./views/Schedule/DetailAsessment"),
  loading: Loading
});

const NoSurat = Loadable({
  loader: () => import("./components/EditData/EditNoSurat"),
  loading: Loading
});

const Rejected = Loadable({
  loader: () => import("./views/Schedule/Rejected"),
  loading: Loading
});

const ManagementSurat = Loadable({
  loader: () => import("./views/ManageSurat/ManageSurat"),
  loading: Loading
});

const AsesiUnderAssessors = Loadable({
  loader: () => import("./views/Schedule/DetailAsesiAssessment"),
  loading: Loading
});

const AsesiUnderAssessment = Loadable({
  loader: () => import("./views/Schedule/AsesiReadyAssign"),
  loading: Loading
});

const PortfolioRoleAsesi = Loadable({
  loader: () => import("./views/Schedule/portfolioRoleAsesi"),
  loading: Loading
});

const Archive = Loadable({
  loader: () => import("./views/Schedule/ArchiveAssessment"),
  loading: Loading
});

const UnitCompetention = Loadable({
  loader: () => import("./views/Competences/UnitCompetention/UnitCompetention"),
  loading: Loading
});

const PersyaratanUmum = Loadable({
  loader: () => import("./components/Detail/PersyaratanUmum"),
  loading: Loading
});

const RestoreData = Loadable({
  loader: () => import("./views/RestoreData/RestoreData"),
  loading: Loading
});

const routes = [
  //menu user managemen
  { path: "/users", exact: true, name: "Users", component: Users },
  {
    path: "/users/add-users",
    name: `${multiLanguage.add} Data`,
    component: InputData
  },
  { path: "/users/edit-users/:user_id", component: EditData },
  {
    path: "/users/:user_id/Asesors",
    name: `${multiLanguage.Edit} Data Asesor`,
    component: EditAssessors
  },
  //menu accessors
  { path: "/Assessors", exact: true, name: "Asesor", component: Assessors },
  { path: "/Assessors/list-skill/:user_id", component: ListSkill },
  {
    path: "/Assessors/pending-competance",
    name: `${multiLanguage.competencePending}`,
    component: PendingCompetance
  },
  {
    path: "/Assessors/schedule_accessors",
    name: `${multiLanguage.schedule} Asesor`,
    component: Schedule_accessors
  },

  //menu applicant
  { path: "/asesi", exact: true, name: "Asesi", component: Applicant },
  {
    path: "/asesi/edit-asesi/:user_id",
    name: `${multiLanguage.Edit} Asesi`,
    component: EditApplicant
  },

  //menu schema
  {
    path: "/schema/main-schema",
    exact: true,
    name: multiLanguage.mainSchema,
    component: MainSchema
  },
  {
    path: "/schema/main-schema/edit-mainSchema/:schema_id",
    component: EditData_mainSchema,
    name: `${multiLanguage.Edit} ${multiLanguage.mainSchema}`
  },
  {
    path: "/schema/main-schema/add-mainCompetence",
    name: `${multiLanguage.add} Data`,
    component: InputData_mainSchema
  },
  {
    path: "/schema/sub-schema",
    exact: true,
    name: multiLanguage.subSchema,
    component: SubSchema
  },
  {
    path: "/Schema/:schema_id/sub_schemas/:sub_schema_id/:sub_schema_number",
    name: `${multiLanguage.Edit} data ${multiLanguage.subSchema}`,
    component: EditData_subSchema
  },
  {
    path: "/schema/sub-schema/add-subSchema",
    name: `${multiLanguage.add} Data`,
    component: InputData_subSchema
  },
  {
    path: "/schema/unit-competention",
    exact: true,
    name: `${multiLanguage.UnitCompetention}`,
    component: UnitCompetention
  },

  //menu assessment
  {
    path: "/assessments/list",
    exact: true,
    name: "Assessments",
    component: Schedule
  },
  {
    path: "/assessments/input-data",
    name: `${multiLanguage.add} ${multiLanguage.schedule} Assessment`,
    component: InputAssessment
  },
  {
    path:
      "/assessments/:assessment_id/applicants/:assessment_applicant_id/portofolio",
    name: "Portofolio Asesi",
    component: Portofolio
  },
  {
    path: "/assessments/:assessment_id/assign",
    name: "Assign Assessment",
    exact: true,
    component: Assign
  },
  {
    path: "/assessments/:assessment_id/assign-admin/:run",
    component: AssignAdmin
  },
  {
    path:
      "/assessments/:assessment_id/assign-applicant/:sub_schema_number/:run",
    component: AssignApplicant
  },
  {
    path: "/assessments/:assessment_id/generate",
    component: GenerateLetters
  },
  {
    path: "/assessments/:assessment_id/assign-assessors",
    component: AssignAssessors
  },
  {
    path: "/assessments/:assessment_id/detail",
    name: "Detail Assessment",
    component: DetailAssessment
  },
  {
    path: "/assessments/:assessment_id/assign/:assessor_id",
    name: "Detail Asesi on Assessments",
    component: AsesiUnderAssessors
  },
  {
    path: "/assessments/:assessment_id/detail-asesi",
    name: "Detail Asesi on Assessments",
    component: AsesiUnderAssessment
  },
  {
    path: "/assessments/:assessment_id/letters/:assessment_letter_id",
    name: `${multiLanguage.Edit} No.Surat`,
    component: NoSurat
  },
  {
    path: "/assessments/:assessment_id/plenos/:run",
    name: `${multiLanguage.create} List Pleno`,
    component: AssignPleno
  },
  {
    path: "/assessments/submission",
    exact: true,
    name: "Submissions",
    component: Submissions
  },
  {
    path: "/assessments/rejected",
    exact: true,
    name: `${multiLanguage.list} ${multiLanguage.Assessment} ${multiLanguage.reject}`,
    component: Rejected
  },
  {
    path: "/assessments/:assessment_id/portfolio",
    name: "Portfolio Assessment",
    component: PortfolioRoleAsesi
  },
  {
    path: "/assessments/archives",
    exact: true,
    name: `${multiLanguage.archives} ${multiLanguage.schedule}`,
    component: Archive
  },
  {
    path: "/applicants/:applicant_id/persyaratan-umum",
    name: "Persyaratan Umum Asesi",
    component: PersyaratanUmum
  },

  //menu TUK
  { path: "/tuk", exact: true, name: "TUK", component: TUK },
  {
    path: "/tuk/add-tuk",
    name: `${multiLanguage.add} Data TUK`,
    component: InputData_tuk
  },
  {
    path: "/tuk/edit-tuk/:tuk_id",
    name: `${multiLanguage.Edit}`,
    component: EditTuk
  },

  //alumni
  {
    path: "/alumnis",
    exact: true,
    name: "Alumni",
    component: Alumni
  },
  {
    path: "/alumnis/add-alumni",
    component: InputAlumni,
    name: "Tambah Data Alumni"
  },
  {
    path: "/alumnis/edit-alumni/:alumni_id",
    component: EditAlumni,
    name: "Tambah Data Alumni"
  },

  //management surat
  {
    path: "/management-letters",
    component: ManagementSurat,
    name: multiLanguage.mainSchema,
    exact: true
  },

  // etc
  {
    path: "/",
    exact: true,
    name: multiLanguage.home,
    component: DefaultLayout
  },
  { path: "/dashboard", component: Dashboard },
  { path: "/404", component: NotFound },
  { path: "/articles/:article_id", component: News },
  {
    path: "/message",
    name: multiLanguage.notif,
    component: Notif
  },

  //master data
  {
    path: "/portfolios",
    exact: true,
    name: "Master Data",
    component: Portfolios
  },
  {
    path: "/portfolios/input",
    name: `${multiLanguage.add} data Portofolio`,
    component: InputData_Portfolios
  },
  {
    path: "/portfolios/edit-portfolios/:master_portfolio_id",
    name: `${multiLanguage.Edit} Master Portofolios`,
    component: EditData_portfoliosMaster
  },

  //Restore Data
  {
    path: "/restore-data",
    exact: true,
    name: "Restore Data",
    component: RestoreData
  }
];

export default routes;
