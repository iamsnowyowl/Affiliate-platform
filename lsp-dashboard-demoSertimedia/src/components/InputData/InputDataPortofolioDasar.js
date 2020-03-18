import React, { Component } from "react";
import { Link, Redirect } from "react-router-dom";
import { Button, Row, Col, Label, Alert } from "reactstrap";
import { Checkbox, Select } from "antd";
import {
  path_masterData,
  path_schemaViews,
  getData,
  baseUrl,
  formatCapitalize
} from "../config/config";
import {
  AvForm,
  AvField,
  AvGroup,
  AvInput,
  AvFeedback
} from "availity-reactstrap-validation";
import Axios from "axios";

import "../../css/loaderComponent.css";
import "antd/dist/antd.css";
import { multiLanguage } from "../Language/getBahasa";
import LabelRequired from "../Label/LabelRequired";
import { Digest } from "../../containers/Helpers/digest";

const { Option } = Select;

type Props = {
  type: any
};

class InputDataPortofolioDasar extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        sub_schema_number: "",
        form_name: "",
        document_state: [],
        apl_document_state: [],
        acs_document_state: []
      },
      payload: [],
      inputPortofolio: false,
      message: "",
      hidden: true,
      subField: true,
      multiple: "0"
    };
  }

  Get(options, response) {
    Axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  handleChange = (event, errors, values) => {
    this.setState({
      [event.target.name]: event.target.value,
      errors,
      values
    });
  };

  componentDidMount() {
    const path = "/public" + path_schemaViews;
    if (this.state.type === undefined) {
      this.setState({
        type: "UMUM"
      });
    }
    const auth = Digest(path, "GET");
    var link = baseUrl + path;

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: link + "?limit=100"
    };

    this.Get(options, "payload");
  }

  handleType = event => {
    if (event.target.value === "0") {
      this.setState({ type: "UMUM", subField: true });
    } else {
      this.setState({ type: "DASAR", subField: false });
    }
  };

  onChange = event => {
    if (event.target.checked === true) {
      this.setState({
        multiple: "1"
      });
    } else {
      this.setState({
        multiple: "0"
      });
    }
  };

  handleChangeDocumentState = value => {
    this.setState({
      document_state: value
    });
  };

  handleChangeRoleStateACS = value => {
    this.setState({
      acs_document_state: value
    });
  };

  handleChangeRoleStateASESI = value => {
    this.setState({
      apl_document_state: value
    });
  };

  handleSubmit = event => {
    event.preventDefault();
    this.setState({ fireRedirect: true });
    const { type } = this.props;

    var formData = new FormData();
    formData.append("type", type === 0 ? "UMUM" : "DASAR");
    formData.append("form_type", "file");
    formData.append("form_name", formatCapitalize(this.state.form_name));
    formData.append("is_multiple", this.state.multiple);
    formData.append("document_state", this.state.document_state);
    formData.append("apl_document_state", this.state.apl_document_state);
    formData.append("acs_document_state", this.state.acs_document_state);
    formData.append("sub_schema_number", this.state.sub_schema_number);

    Axios(getData(path_masterData, "POST", formData))
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ inputPortofolio: true });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        this.setState({
          response: responseJSON.data.error.code
        });

        switch (this.state.response) {
          case 400:
            this.setState({
              hidden: false,
              message: multiLanguage.alertInput
            });
            break;

          case 409:
            this.setState({
              hidden: false,
              message: multiLanguage.alertAlready
            });
            break;

          default:
            break;
        }
      });
  };

  render() {
    if (this.state.inputPortofolio) {
      return <Redirect to={"/portfolios"} />;
    }
    return (
      <div>
        <AvForm
          action=""
          encType="multipart/form-data"
          className="form-horizontal"
        >
          <AvGroup row>
            <Col md="2">
              <LabelRequired
                fors="sub_schema_number"
                label={multiLanguage.subSchema}
              />
            </Col>
            <Col xs="5" md="7">
              <AvField
                type="select"
                name="sub_schema_number"
                onChange={this.handleChange}
                required
              >
                <option value="">{`${multiLanguage.select} ${multiLanguage.subSchema}`}</option>
                {this.state.payload.map(
                  ({ sub_schema_number, sub_schema_name }) => {
                    return (
                      <option value={sub_schema_number} key={sub_schema_number}>
                        {sub_schema_name}
                      </option>
                    );
                  }
                )}
              </AvField>
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <LabelRequired fors="form_name" label={multiLanguage.formName} />
            </Col>
            <Col xs="5" md="3">
              <AvInput
                type="text"
                style={{ borderColor: "black" }}
                id="form_name"
                name="form_name"
                placeholder={multiLanguage.formName}
                onChange={this.handleChange}
                required
              />
              <AvFeedback>{multiLanguage.alertField}</AvFeedback>
            </Col>
            <Col md="2">{`${multiLanguage.multipleFile} `}</Col>
            <Col md="auto">
              <Checkbox onChange={this.onChange} />{" "}
              {this.state.multiple !== "1" ? "Tidak" : "Ya"}
            </Col>
          </AvGroup>

          <AvGroup row>
            <Col md="2">
              <Label for="type">Munculkan Dokumen Pada Saat</Label>
            </Col>
            <Col xs="7" md="7">
              <Select
                mode="multiple"
                style={{ width: "100%" }}
                placeholder={`${multiLanguage.select} State Asesment`}
                onChange={this.handleChangeDocumentState}
                optionLabelProp="label"
              >
                <Option
                  value="ADMIN_CONFIRM_FORM"
                  label={multiLanguage.stateReadyPraAssessment}
                >
                  {multiLanguage.stateReadyPraAssessment}
                </Option>
                <Option
                  value="ON_REVIEW_APPLICANT_DOCUMENT"
                  label={multiLanguage.reviewDoc}
                >
                  {multiLanguage.reviewDoc}
                </Option>
                <Option
                  value="ON_COMPLETED_REPORT"
                  label={multiLanguage.PraAssessmentCompleted}
                >
                  {multiLanguage.PraAssessmentCompleted}
                </Option>
                <Option value="REAL_ASSESSMENT" label="Real Assessment">
                  Real Assessment
                </Option>
                <Option value="PLENO_DOCUMENT_COMPLETED" label="Pleno">
                  Pleno
                </Option>
                <Option
                  value="PLENO_REPORT_READY"
                  label={multiLanguage.PlenoFinish}
                >
                  {multiLanguage.PlenoFinish}
                </Option>
                <Option
                  value="PRINT_CERTIFICATE"
                  label={multiLanguage.certificate}
                >
                  {multiLanguage.certificate}
                </Option>
                <Option value="COMPLETED" label={multiLanguage.completed}>
                  {multiLanguage.completed}
                </Option>
              </Select>
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <Label for="type">{`Munculkan Dokumen Pada ${multiLanguage.assessors} Saat`}</Label>
            </Col>
            <Col xs="7" md="7">
              <Select
                mode="multiple"
                style={{ width: "100%" }}
                placeholder={`${multiLanguage.select} State Asesment`}
                onChange={this.handleChangeRoleStateACS}
                optionLabelProp="label"
              >
                <Option
                  value="ADMIN_CONFIRM_FORM"
                  label={multiLanguage.stateReadyPraAssessment}
                >
                  {multiLanguage.stateReadyPraAssessment}
                </Option>
                <Option
                  value="ON_REVIEW_APPLICANT_DOCUMENT"
                  label={multiLanguage.reviewDoc}
                >
                  {multiLanguage.reviewDoc}
                </Option>
                <Option
                  value="ON_COMPLETED_REPORT"
                  label={multiLanguage.PraAssessmentCompleted}
                >
                  {multiLanguage.PraAssessmentCompleted}
                </Option>
                <Option value="REAL_ASSESSMENT" label="Real Assessment">
                  Real Assessment
                </Option>
                <Option value="PLENO_DOCUMENT_COMPLETED" label="Pleno">
                  Pleno
                </Option>
                <Option
                  value="PLENO_REPORT_READY"
                  label={multiLanguage.PlenoFinish}
                >
                  {multiLanguage.PlenoFinish}
                </Option>
                <Option
                  value="PRINT_CERTIFICATE"
                  label={multiLanguage.certificate}
                >
                  {multiLanguage.certificate}
                </Option>
                <Option value="COMPLETED" label={multiLanguage.completed}>
                  {multiLanguage.completed}
                </Option>
              </Select>
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <Label for="type">{`Munculkan Dokumen Pada ${multiLanguage.asesi} Saat`}</Label>
            </Col>
            <Col xs="7" md="7">
              <Select
                mode="multiple"
                style={{ width: "100%" }}
                placeholder={`${multiLanguage.select} State Asesment`}
                onChange={this.handleChangeRoleStateASESI}
                optionLabelProp="label"
              >
                <Option
                  value="ADMIN_CONFIRM_FORM"
                  label={multiLanguage.stateReadyPraAssessment}
                >
                  {multiLanguage.stateReadyPraAssessment}
                </Option>
                <Option
                  value="ON_REVIEW_APPLICANT_DOCUMENT"
                  label={multiLanguage.reviewDoc}
                >
                  {multiLanguage.reviewDoc}
                </Option>
                <Option
                  value="ON_COMPLETED_REPORT"
                  label={multiLanguage.PraAssessmentCompleted}
                >
                  {multiLanguage.PraAssessmentCompleted}
                </Option>
                <Option value="REAL_ASSESSMENT" label="Real Assessment">
                  Real Assessment
                </Option>
                <Option value="PLENO_DOCUMENT_COMPLETED" label="Pleno">
                  Pleno
                </Option>
                <Option
                  value="PLENO_REPORT_READY"
                  label={multiLanguage.PlenoFinish}
                >
                  {multiLanguage.PlenoFinish}
                </Option>
                <Option
                  value="PRINT_CERTIFICATE"
                  label={multiLanguage.certificate}
                >
                  {multiLanguage.certificate}
                </Option>
                <Option value="COMPLETED" label={multiLanguage.completed}>
                  {multiLanguage.completed}
                </Option>
              </Select>
            </Col>
          </AvGroup>
          <Alert
            color="danger"
            hidden={this.state.hidden}
            className="text-center"
          >
            {this.state.message}
          </Alert>
        </AvForm>
        <p />
        <Row>
          <Col md="1.5">
            <Link to={path_masterData}>
              <Button type="submit" size="md" className="btn-danger">
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </Button>
            </Link>
          </Col>
          <Col md="1.5">
            <Button
              className="btn btn-success Btn-Submit"
              type="submit"
              size="md"
              color="success"
              onClick={this.handleSubmit}
            >
              <i className="fa fa-check" /> {multiLanguage.submit}
            </Button>
          </Col>
        </Row>
      </div>
    );
  }
}

export default InputDataPortofolioDasar;
