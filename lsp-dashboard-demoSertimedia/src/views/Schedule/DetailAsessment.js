import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  Button,
  Input,
  Table
} from "reactstrap";
import { Modal } from "antd";
import Axios from "axios";
import LoadingOverlay from "react-loading-overlay";

import { path_assessments, getData } from "../../components/config/config";
import { multiLanguage } from "../../components/Language/getBahasa";

export default class DetailAssessment extends Component {
  constructor(props) {
    super(props);
    this.state = {
      payloadAssessment: [],
      payloadAsesi: [],
      payloadAsesor: [],
      payloadAdmin: [],
      modal: false,
      errorTrial: false,
      messageModal: "",
      status: "",
      loading: false
    };
    this.toggle = this.toggle.bind(this);
  }

  toggle = () => {
    this.setState({
      modal: !this.state.modal
    });
  };

  toggleError = () => {
    this.setState({
      errorTrial: !this.state.errorTrial
    });
  };

  Get(options, response) {
    Axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  componentDidMount() {
    const assessment_id = this.props.match.params.assessment_id;
    const pathAssessment = path_assessments + "/" + assessment_id;
    const pathAsesor = path_assessments + "/" + assessment_id + "/assessors";
    const pathAsesi = path_assessments + "/" + assessment_id + "/applicants";
    const pathAdmin = path_assessments + "/" + assessment_id + "/admins";
    this.Get(getData(pathAssessment, "GET"), "payloadAssessment");
    this.Get(getData(pathAsesor, "GET"), "payloadAsesor");
    this.Get(getData(pathAsesi, "GET"), "payloadAsesi");
    this.Get(getData(pathAdmin, "GET"), "payloadAdmin");
  }

  handleChange = event => {
    this.setState({
      [event.target.name]: event.target.value,
      status: event.target.value
    });
  };

  handleSubmit = event => {
    this.setState({ loading: true, modal: false });
    this.toggle();
    const assessment_id = this.props.match.params.assessment_id;
    event.preventDefault();
    const path =
      path_assessments +
      "/" +
      assessment_id +
      "/change_state/" +
      this.state.status;
    Axios(getData(path, "PUT"))
      .then(res => {
        this.setState({ loading: false });
        window.location.reload();
      })
      .catch(error => {
        const messageError = error.response.data.error.message;
        this.setState({
          loading: false,
          messageModal: messageError
        });
        this.error();
      });
  };

  cancel = () => {
    this.setState({
      modal: false
    });
  };

  error = () => {
    Modal.error({
      title: "Error",
      content: this.state.messageModal
    });
  };

  render() {
    const {
      title,
      longitude,
      latitude,
      address,
      tuk_name,
      last_activity_state
    } = this.state.payloadAssessment;
    var status = "";
    switch (last_activity_state) {
      case "INIT":
        status = "-";
        break;
      case "ADMIN_CONFIRM_FORM":
        status = multiLanguage.stateReadyPraAssessment;
        break;
      case "ON_REVIEW_APPLICANT_DOCUMENT":
        status = multiLanguage.stateReview;
        break;
      case "ON_COMPLETED_REPORT":
        status = multiLanguage.statePraAsesment;
        break;
      case "REAL_ASSESSMENT":
        status = multiLanguage.stateReal;
        break;
      case "PLENO_DOCUMENT_COMPLETED":
        status = "Pleno";
        break;
      case "PLENO_REPORT_READY":
        status = multiLanguage.statePlenoFinish;
        break;
      case "PRINT_CERTIFICATE":
        status = multiLanguage.certificate;
        break;
      case "COMPLETED":
        status = multiLanguage.completed;
        break;

      default:
        break;
    }
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading...">
        <div>
          <div>
            <Modal
              title={`${multiLanguage.change} State Asesment`}
              visible={this.state.modal}
              onOk={this.handleSubmit}
              onCancel={this.cancel}
            >
              <Input
                type="select"
                id="last_activity_state"
                name="last_activity_state"
                onChange={this.handleChange}
                required
              >
                <option value="">{`${multiLanguage.select} State Asesment`}</option>
                <option value="ADMIN_CONFIRM_FORM">
                  {multiLanguage.stateReadyPraAssessment}
                </option>
                <option value="ON_REVIEW_APPLICANT_DOCUMENT">
                  {multiLanguage.reviewDoc}
                </option>
                <option value="ON_COMPLETED_REPORT">
                  {multiLanguage.PraAssessmentCompleted}
                </option>
                <option value="REAL_ASSESSMENT">
                  {multiLanguage.stateReal}
                </option>
                <option value="PLENO_DOCUMENT_COMPLETED">Pleno</option>
                <option value="PLENO_REPORT_READY">
                  {multiLanguage.PlenoFinish}
                </option>
                <option value="PRINT_CERTIFICATE">
                  {multiLanguage.certificate}
                </option>
                <option value="COMPLETED">{multiLanguage.completed}</option>
              </Input>
            </Modal>
          </div>
          <Card>
            <CardHeader style={{ textAlign: "center" }}>
              <b>Detail Asesment</b>
            </CardHeader>
            <CardBody>
              <Table striped responsive>
                <tbody>
                  <tr>
                    <th>{multiLanguage.assessmentName}</th>
                    <td>{title}</td>
                  </tr>
                  <tr>
                    <th>{multiLanguage.tukName}</th>
                    <td>{tuk_name}</td>
                  </tr>
                  <tr>
                    <th>{multiLanguage.address}</th>
                    <td>
                      {address === "undefined" ? (
                        <div>-</div>
                      ) : (
                        <a
                          href={`https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`}
                          target="_blank"
                        >
                          {address}
                        </a>
                      )}
                    </td>
                  </tr>
                  <tr>
                    <th>{multiLanguage.countAsesor}</th>
                    <td>
                      {" "}
                      {this.state.payloadAsesor.length === 0
                        ? "Belum Ada Asesor"
                        : `${this.state.payloadAsesor.length} Orang`}
                    </td>
                  </tr>
                  <tr>
                    <th>{multiLanguage.countAsesi}</th>
                    <td>
                      {" "}
                      {this.state.payloadAsesi.length === 0
                        ? "Belum Ada Asesi"
                        : `${this.state.payloadAsesi.length} Orang`}
                    </td>
                  </tr>
                  <tr>
                    <th>{multiLanguage.countAdmin}</th>
                    <td>
                      {this.state.payloadAdmin.length === 0
                        ? "Belum Ada Admin"
                        : `${this.state.payloadAdmin.length} Orang`}
                    </td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td>{status}</td>
                  </tr>
                  <tr>
                    <th>
                      <Button
                        size="md"
                        onClick={this.toggle}
                        className="change-state"
                      >
                        {`${multiLanguage.change} State`}
                      </Button>
                    </th>
                  </tr>
                </tbody>
              </Table>
            </CardBody>
            <CardFooter>
              <Link to={path_assessments + "/list"}>
                <Button className="btn-danger" type="submit" size="md">
                  <i className="fa fa-chevron-left" /> {multiLanguage.back}
                </Button>
              </Link>
            </CardFooter>
          </Card>
        </div>
      </LoadingOverlay>
    );
  }
}
