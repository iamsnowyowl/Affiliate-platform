import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Row,
  Col,
  Card,
  CardHeader,
  CardBody,
  Button,
  Modal,
  ModalHeader,
  ModalBody,
  ModalFooter
} from "reactstrap";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";
import Axios from "axios";
import { Popconfirm } from "antd";

import {
  baseUrl,
  path_jointRequest,
  path_assessments,
  path_applicant,
  formatDate
} from "../../components/config/config";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import TableList from "../../components/ListTables/TableList";
import { Digest } from "../../containers/Helpers/digest";
// import style from '../../css/style.css';
import ListAssessmentJoin from "./ListAssessmentJoin";
import EmptyData from "../../components/EmptyData/EmptyData";

class Applicant extends Component {
  constructor(props) {
    super(props);
    this.state = {
      visible: false,
      note: "",
      payloadListAssessment: [],
      applicant_id: "",
      join_request_id: "",
      sub_schema_name: ""
    };
  }

  handleCancel = e => {
    this.setState({
      visible: false
    });
  };

  showModal = value => {
    this.setState({
      visible: true,
      applicant_id: value.applicant_id,
      join_request_id: value.join_request_id,
      sub_schema_name: value.sub_schema_name
    });
    const auth = Digest(path_assessments, "GET");
    var link =
      baseUrl +
      path_assessments +
      "?sub_schema_number=" +
      value.sub_schema_number +
      "&last_activity_state=ADMIN_CONFIRM_FORM,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_MEMBER_READY,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,PRINT_CERTIFICATE";

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: link
    };
    Axios(options).then(response => {
      this.setState({
        payloadListAssessment: response.data.data
      });
    });
  };

  reject = value => {
    this.setState({
      loading: true
    });
    const { join_request_id } = value;
    const auth = Digest(path_jointRequest + "/" + join_request_id, "DELETE");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path_jointRequest + "/" + join_request_id
    };
    Axios(options)
      .then(res => {
        this.setState({
          loading: false
        });
        window.location.reload();
      })
      .catch(error => {
        this.setState({
          loading: false
        });
        if (error) {
          NotificationManager.error(
            "Terjadi Kesalahan saat membatalkan",
            "Error",
            5000
          );
        }
      });
  };

  cancel = () => {
    console.log("cancel reject");
  };

  render() {
    const columns = [
      {
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        sorter: true,
        width: "10%",
        render: (value, row) => {
          return value + " " + row.last_name;
        }
      },
      {
        key: "sub_schema_name",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.subSchemaName}
          </h5>
        ),
        dataIndex: "sub_schema_name",
        width: "10%",
        render: value => {
          return value;
        }
      },
      {
        key: "request_status",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.purposeAssessment}
          </h5>
        ),
        dataIndex: "request_status",
        width: "10%",
        render: (value, row) => {
          return value === "S" ? "Sertifikasi" : "Sertifikasi Ulang";
        }
      },
      {
        key: "created_date",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.requestDate}
          </h5>
        ),
        dataIndex: "created_date",
        width: "10%",
        render: value => {
          return formatDate(value);
        }
      },
      {
        key: "applicant_id",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.generalRequiret}
          </h5>
        ),
        dataIndex: "applicant_id",
        width: "10%",
        align:'center',
        render: (value, row) => {
          return (
            <div>
              <Link
                to={`${path_applicant}/${row.applicant_id}/persyaratan-umum`}
              >
                <Button className="btn-primary-sm" title={multiLanguage.generalRequiret}>
                  Berkas asesi
                </Button>
              </Link>
            </div>
          );
        }
      },
      {
        key: "join_request_id",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "join_request_id",
        width: "10%",
        render: (value, row) => {
          return (
            <div>
              <Button
                className="btn btn-success"
                onClick={() => this.showModal(row)}
                style={{ width: "91px" }}
              >
                {multiLanguage.approve}
              </Button>{" "}
              <Popconfirm
                title="Apakah anda yakin akan membatalkan asesi ini?"
                onConfirm={() => this.reject(row)}
                onCancel={this.cancel}
                okText={multiLanguage.yes}
                cancelText={multiLanguage.no}
              >
                <Button color="danger" title={multiLanguage.delete}>
                  {multiLanguage.reject}
                </Button>
              </Popconfirm>
            </div>
          );
        }
      }
    ];
    const {
      payloadListAssessment,
      visible,
      showModal,
      applicant_id,
      join_request_id,
      sub_schema_name
    } = this.state;
    return (
      <div className="animated fadeIn">
        <Modal isOpen={visible} toggle={showModal} size="lg">
          <ModalHeader>
            Daftar Assessment untuk schema {sub_schema_name}
          </ModalHeader>
          <ModalBody>
            {payloadListAssessment.length !== 0 ? (
              <ListAssessmentJoin
                payloadListAssessment={payloadListAssessment}
                applicant_id={applicant_id}
                join_request_id={join_request_id}
              />
            ) : (
              <EmptyData label="Tidak ada jadwal yang sesuai dengan shema ini" />
            )}
            <ModalFooter>
              <Button color="danger" onClick={this.handleCancel}>
                {multiLanguage.cancel}
              </Button>
            </ModalFooter>
          </ModalBody>
        </Modal>

        <Card>
          <CardHeader>
            <Row>
              <Col md="6">
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  Daftar Peserta
                </h5>
              </Col>
            </Row>
          </CardHeader>
          <CardBody>
            <TableList
              columns={columns}
              urls={baseUrl + path_jointRequest}
              path={path_jointRequest}
              rowKeys={record => record.join_request_id}
            />
          </CardBody>
        </Card>
        <NotificationContainer />
      </div>
    );
  }
}

export default Applicant;
