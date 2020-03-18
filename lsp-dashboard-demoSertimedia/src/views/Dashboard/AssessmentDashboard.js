import React, { Component } from "react";
import $ from "jquery";
import { Card, CardHeader, CardBody } from "reactstrap";
import { multiLanguage } from "../../components/Language/getBahasa";
import {
  baseUrl,
  path_assessmentsDashboard,
  query_assessments,
  formatDate
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import "../../css/Table.css";

export default class AssessmentDashboard extends Component {
  componentDidMount() {
    var url = `${baseUrl}${path_assessmentsDashboard}${query_assessments}&limit=100&last_activity_state=TUK_COMPLETE_FORM,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_DOCUMENT_COMPLETED,ADMIN_CONFIRM_FORM,PLENO_REPORT_READY,ASSESSMENT_REJECTED,PRINT_CERTIFICATE`;
    $.DataTable = require("datatables.net-bs4");
    $(document).ready(function() {
      // pageScroll();
      $("#assessments").DataTable({
        responsive: true,
        scrollX: true,
        scrollY: "30vh",
        scrollCollapse: true,
        lengthChange: false,
        animate: true,
        ajax: {
          url: url,
          beforeSend: function(ark) {
            const authentication = Digest(path_assessmentsDashboard, "GET");
            ark.setRequestHeader("Authorization", authentication.digest);
            ark.setRequestHeader("X-Lsp-Date", authentication.date);
          },
          dataFilter: function(data) {
            var json = JSON.parse(data);
            for (var index = 0; index < json.data.length; index++) {
              const element = json.data[index];
              const value = element.start_date;
              var start_date = formatDate(value);
              element.start_date = start_date;
              switch (element.last_activity_state) {
                case "TUK_COMPLETE_FORM":
                  element.last_activity_state =
                    multiLanguage.stateRequestAssessment;
                  break;

                case "ON_REVIEW_APPLICANT_DOCUMENT":
                  element.last_activity_state = multiLanguage.stateReview;
                  break;

                case "ON_COMPLETED_REPORT":
                  element.last_activity_state = multiLanguage.statePraAsesment;
                  break;

                case "REAL_ASSESSMENT":
                  element.last_activity_state = multiLanguage.stateReal;
                  break;

                case "PLENO_DOCUMENT_COMPLETED":
                  element.last_activity_state = "Pleno";
                  break;

                case "ADMIN_CONFIRM_FORM":
                  element.last_activity_state =
                    multiLanguage.stateReadyPraAssessment;
                  break;

                case "PLENO_REPORT_READY":
                  element.last_activity_state = multiLanguage.statePlenoFinish;
                  break;

                case "ASSESSMENT_REJECTED":
                  element.last_activity_state =
                    multiLanguage.stateAsesmentReject;
                  break;

                case "PRINT_CERTIFICATE":
                  element.last_activity_state = multiLanguage.printCertificate;
                  break;

                case "TUK_SEND_REQUEST_ASSESSMENT":
                  element.last_activity_state = multiLanguage.waitingConfirm;
                  break;

                default:
                  break;
              }

              element.tags = element.last_activity_state;
              switch (element.tags) {
                case multiLanguage.stateRequestAssessment:
                  element.tags =
                    '<ul class="stateRequestAssessment"><li/></ul>';
                  break;

                case multiLanguage.stateReview:
                  element.tags = '<ul class="listReviewDocument"><li/></ul>';
                  break;

                case multiLanguage.stateReadyPraAssessment:
                  element.tags =
                    '<ul class="listReadyPraAssessment"><li/></ul>';
                  break;

                case multiLanguage.stateReal:
                  element.tags = '<ul class="listRealAssessment"><li/></ul>';
                  break;

                case multiLanguage.statePraAsesment:
                  element.tags = '<ul class="listRealAssessment"><li/></ul>';
                  break;

                case "Pleno":
                  element.tags = '<ul class="listPleno"><li/></ul>';
                  break;

                case multiLanguage.statePlenoFinish:
                  element.tags = '<ul class="listPlenoFinish"><li/></ul>';
                  break;

                case multiLanguage.printCertificate:
                  element.tags = '<ul class="listPrintCertificate"><li/></ul>';
                  break;

                case multiLanguage.stateAsesmentReject:
                  element.tags = '<ul class="listReject"><li/></ul>';
                  break;

                case multiLanguage.waitingConfirm:
                  element.tags = '<ul class="liWaitingConfirmation"><li/></ul>';
                  break;

                default:
                  break;
              }

              for (let index = 0; index < element.assessor.length; index++) {
                const elementAssessors = element.assessor[index];
                var full_name =
                  elementAssessors.first_name +
                  " " +
                  elementAssessors.last_name;
              }

              switch (element.assessor.length) {
                case 0:
                  element.assessor = "-";
                  break;

                default:
                  element.assessor = full_name;
                  break;
              }
            }
            json.recordsTotal = json.count;
            json.recordsFiltered = json.count;
            return JSON.stringify(json);
          },
          error: function(error) {
            return error;
          },
          statusCode: {
            401: function() {
              localStorage.clear();
              window.location.replace("/login");
            }
          }
        },
        serverSide: true,
        processing: true,
        searching: false,
        paging: false,
        info: false,
        order: [3, "desc"],
        oLanguage: {
          sProcessing: '<div class="lds-ripple"><div></div><div></div></div>'
        },
        columnDefs: [
          {
            name: "tags",
            data: "tags",
            title: "Tags",
            width: "5%",
            targets: 0,
            sortable: false,
            filterable: true
          },
          {
            name: "title",
            data: "title",
            title: multiLanguage.assessmentName,
            width: "10%",
            targets: 1,
            sortable: false,
            filterable: true
          },
          {
            name: "start_date",
            data: "start_date",
            title: multiLanguage.startDate,
            width: "10%",
            targets: 2,
            sortable: false,
            filterable: true
          },
          {
            name: "tuk_name",
            data: "tuk_name",
            title: "TUK",
            width: "10%",
            targets: 3,
            sortable: false,
            filterable: true
          },
          {
            name: "last_activity_state",
            data: "last_activity_state",
            title: "Status",
            width: "10%",
            targets: 4,
            sortable: false,
            filterable: true
          },
          {
            name: "assessor",
            data: "assessor",
            title: multiLanguage.assessors,
            width: "5%",
            targets: 5,
            sortable: false,
            filterable: true
          }
        ],
        initComplete: function() {
          var $el = $(".dataTables_scrollBody");
          function anim() {
            var st = $el.scrollTop();
            var sb = $el.prop("scrollHeight") - $el.innerHeight();
            $el.animate({ scrollTop: st < sb / 10 ? sb : 0 }, 7000, anim);
          }
          function stop() {
            $el.stop();
          }
          anim();
          $el.hover(stop, anim);
        }
      });
    });
  }
  render() {
    return (
      <div className="animated fadeIn">
        <Card className="tags">
          <CardHeader className="tags">
            <ul>
              <li className="stateRequestAssessment">
                {multiLanguage.stateRequestAssessment}
              </li>
              <li className="liReviewDocument">{multiLanguage.reviewDoc}</li>
              <li className="liReadyPraAssessment">
                {multiLanguage.PraAssessmentCompleted}
              </li>
              <li className="liRealAssessment">
                Real {multiLanguage.Assessment}
              </li>
              <li className="liPleno">Pleno</li>
              <li className="liPlenoFinish">{multiLanguage.PlenoFinish}</li>
              <li className="liPrintCertificate">
                {multiLanguage.certificate}
              </li>
              <li className="liReject">{multiLanguage.stateAsesmentReject}</li>
            </ul>
          </CardHeader>
          <CardBody>
            <table
              id="assessments"
              className="table table-striped table-bordered"
              style={{ width: "100%" }}
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}
