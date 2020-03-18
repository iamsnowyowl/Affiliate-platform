import React, { Component } from "react";
import { Link, Redirect } from "react-router-dom";
import {
  Card,
  CardBody,
  Row,
  Col,
  Input,
  Button,
  Alert,
  Label
} from "reactstrap";
import { Select, Spin } from "antd";

import { Digest } from "../../containers/Helpers/digest";
import { path_assessments, baseUrl, path_users } from "../config/config";
import Axios from "axios";
import { multiLanguage } from "../Language/getBahasa";

import "antd/dist/antd.css";
import LoadingOverlay from "react-loading-overlay";

const { Option } = Select;

export default class InputData_Plenos extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        pleno_id: "",
        position: "",
        pleno_date: ""
      },
      assignPleno: false,
      hidden: true,
      message: "",
      overlay: false,
      payloadAssessment: [],
      payload: [],
      value: [],
      fetching: false
    };
  }

  Get(options, response) {
    Axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  componentDidMount() {
    const assessment_id = this.props.match.params.assessment_id;

    const authAssessment = Digest(
      path_assessments + "/" + assessment_id,
      "GET"
    );
    const authAsesors = Digest(path_users, "GET");

    const options = {
      method: authAssessment.method,
      headers: {
        Authorization: authAssessment.digest,
        "X-Lsp-Date": authAssessment.date
      },
      url: baseUrl + path_assessments + "/" + assessment_id,
      data: null
    };
    const optionAssessors = {
      method: authAsesors.method,
      headers: {
        Authorization: authAsesors.digest,
        "X-Lsp-Date": authAsesors.date
      },
      url: `${baseUrl}${path_users}?limit=100&role_code=ACS,ADM,SUP`,
      data: null
    };

    this.Get(options, "payloadAssessment");
    this.Get(optionAssessors, "payload");
  }

  handleChange = event => {
    this.setState({
      [event.target.name]: event.target.value,
      fetching: true
    });
  };

  handleSubmit = event => {
    event.preventDefault();
    this.setState({
      overlay: true
    });
    const assessment_id = this.props.match.params.assessment_id;
    var data = {};
    const authentication = Digest(
      path_assessments + "/" + assessment_id + "/plenos",
      "POST"
    );
    data["pleno_id"] = this.state.pleno_id;
    data["position"] = this.state.position;
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date,
        "Content-Type": "multipart/form-data"
      },
      url: `${baseUrl}${path_assessments}/${assessment_id}/plenos`,
      data: data
    };
    Axios(options)
      .then(res => {
        if (res.status === 201) {
          this.setState({
            assignPleno: true
          });
        } else {
          return;
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
              message: multiLanguage.alertInput,
              overlay: false
            });
            break;
          case 409:
            this.setState({
              hidden: false,
              overlay: false,
              message: multiLanguage.userAlready
            });
            break;

          default:
            break;
        }
      });
  };

  onChange = value => {
    this.setState({
      value,
      payload: [],
      fetching: false,
      pleno_id: value.key
    });
  };

  onSearch = value => {
    this.setState({
      fetching: true
    });
    if (value !== "") {
      const authAsesors = Digest(path_users, "GET");
      const optionAssessors = {
        method: authAsesors.method,
        headers: {
          Authorization: authAsesors.digest,
          "X-Lsp-Date": authAsesors.date
        },
        url: `${baseUrl}${path_users}?limit=100&&role_code=ACS,ADM,SUP&search=${value}`,
        data: null
      };
      Axios(optionAssessors).then(response => {
        this.setState({
          payload: response.data.data,
          fetching: false
        });
      });
    } else {
      this.setState({
        payload: [],
        fetching: false
      });
    }
  };

  render() {
    console.log("cari", this.state.value);
    const { run, assessment_id } = this.props.match.params;
    const { title } = this.state.payloadAssessment;
    const { fetching, payload, assignPleno } = this.state;
    if (assignPleno) {
      return (
        <Redirect
          to={{
            pathname: path_assessments + "/" + assessment_id + "/assign",
            state: {
              runs: run
            }
          }}
        />
      );
    }
    console.log("payload", payload);
    return (
      <div>
        <LoadingOverlay active={this.state.overlay} spinner text="Loading">
          <Card>
            <CardBody>
              <form onSubmit={this.handleSubmit} name="test-form">
                <Row>
                  <Col md="3">
                    <Label htmlFor="assessment_id">{`${multiLanguage.name} Assessment`}</Label>
                  </Col>
                  <Col xs="5" md="4">
                    <Input
                      type="text"
                      name="assessment_id"
                      defaultValue={title}
                      readOnly
                    />
                  </Col>
                </Row>
                <p />
                <p />
                <Row>
                  <Col md="3">
                    <Label>{multiLanguage.name}</Label>
                  </Col>
                  <Col xs="5" md="4">
                    {/* <Input
                      type="select"
                      style={{ borderColor: "black" }}
                      id="pleno_id"
                      name="pleno_id"
                      onChange={this.handleChange}
                    >
                      <option value="">{`${multiLanguage.select} Staff`}</option>
                      {this.state.payload.map(
                        ({ user_id, first_name }, key) => {
                          return (
                            <option value={user_id} key={user_id}>
                              {" "}
                              {first_name}
                            </option>
                          );
                        }
                      )}
                    </Input> */}
                    <Select
                      showSearch
                      labelInValue
                      // value={value}
                      placeholder={multiLanguage.select + " Staff"}
                      notFoundContent={fetching ? <Spin size="small" /> : null}
                      filterOption={false}
                      onSearch={this.onSearch}
                      onChange={this.onChange}
                      style={{ width: "100%" }}
                    >
                      {payload.map(d => (
                        <Option key={d.user_id}>{d.first_name}</Option>
                      ))}
                    </Select>
                  </Col>
                </Row>
                <p />
                <Row style={{ marginBottom: "15px" }}>
                  <Col md="3">
                    <Label>{multiLanguage.positionPleno}</Label>
                  </Col>
                  <Col xs="5" md="4">
                    <Input
                      style={{ borderColor: "black" }}
                      type="select"
                      name="position"
                      onChange={this.handleChange}
                    >
                      <option value="">{multiLanguage.select}</option>
                      <option value="ketua">Ketua Pleno</option>
                      <option value="anggota">Anggota Pleno</option>
                    </Input>
                  </Col>
                </Row>
                <Row>
                  <Col>
                    <Alert
                      color="danger"
                      hidden={this.state.hidden}
                      className="text-center"
                    >
                      {this.state.message}
                    </Alert>
                  </Col>
                </Row>
                <Row>
                  <Col md="1.5">
                    <Link
                      to={{
                        pathname:
                          path_assessments + "/" + assessment_id + "/assign",
                        state: {
                          runs: run
                        }
                      }}
                    >
                      <Button className="btn-danger" type="submit" size="md">
                        <i className="fa fa-chevron-left" />{" "}
                        {multiLanguage.back}
                      </Button>
                    </Link>
                  </Col>
                  <Col md="1.5" className="Btn-Submit">
                    <Button
                      color="success"
                      type="submit"
                      value="Submit"
                      size="md"
                      onClick={this.handleSubmit}
                    >
                      <i className="fa fa-check" /> {multiLanguage.submit}
                    </Button>
                  </Col>
                </Row>
              </form>
            </CardBody>
          </Card>
        </LoadingOverlay>
      </div>
    );
  }
}
