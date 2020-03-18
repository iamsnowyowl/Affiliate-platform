import React, { Component } from "react";
import { Redirect } from "react-router-dom";
import axios from "axios";
import {
  Button,
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  Col,
  Input,
  Label,
  Alert,
  Modal,
  ModalBody,
  ModalFooter,
  ModalHeader,
  Row
} from "reactstrap";
import { Select, Spin } from "antd";
import { AvGroup, AvForm, AvField } from "availity-reactstrap-validation";
import AvInput from "availity-reactstrap-validation/lib/AvInput";
import FileBase64 from "react-file-base64";
import SignatureCanvas from "react-signature-canvas";
import Radio from "@material-ui/core/Radio";
import RadioGroup from "@material-ui/core/RadioGroup";
import LoadingOverlay from "react-loading-overlay";

import {
  baseUrl,
  path_users,
  path_tuk,
  getData,
  method_put,
  formatCapitalize,
  path_jobs
} from "../../components/config/config";
import "../../css/img.css";
import { multiLanguage } from "../Language/getBahasa";
import FormControlLabel from "@material-ui/core/FormControlLabel";
import { Digest } from "../../containers/Helpers/digest";
import Axios from "axios";
import LabelRequired from "../Label/LabelRequired";

const { Option } = Select;

class EditData extends Component {
  sigPad = {};
  constructor(props) {
    super(props);
    this.state = {
      bahasa: "",
      fireRedirect: false,
      loading: false,
      gender_code: "",
      data: {
        username: "",
        email: "",
        first_name: "",
        last_name: "",
        contact: "",
        place_of_birth: "",
        date_of_birth: "",
        address: "",
        role_code: "",
        nik: "",
        npwp: "",
        picture: "",
        tuk_id: "",
        institution: "",
        registration_number: "",
        nik: "",
        npwp: "",
        jobs_code: ""
      },
      gender: "",
      modalPic: false,
      modalSignature: false,
      editData: false,
      message: "",
      hidden: true,
      subAssessors: true,
      subApplicant: true,
      subManagement: true,
      subTuk: true,
      payload: [],
      payloadTuk: [],
      signature: "",
      fetching: false,
      valueJobs: [],
      payloadJobs: []
    };
  }

  getFiles(files) {
    this.setState({ files: files[0].base64 });
  }

  toggle = () => {
    this.setState({
      modalPic: !this.state.modalPic
    });
  };

  toggleSignature = () => {
    this.setState({
      modalSignature: !this.state.modalSignature
    });
  };

  Get(options, response) {
    axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  componentDidMount() {
    const user_id = this.props.match.params.user_id;
    const pathUsers = path_users + "/" + user_id;
    const pathTUK = path_tuk;
    const authJob = Digest("/public" + path_jobs, "GET");

    const options = {
      method: authJob.method,
      headers: {
        Authorization: authJob.digest,
        "X-Lsp-Date": authJob.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + "/public" + path_jobs + "?limit=100"
    };

    this.Get(getData(pathTUK, "GET"), "payloadTuk");
    Axios(getData(pathUsers, "GET")).then(response => {
      this.setState({
        gender: response.data.data.gender_code,
        payload: response.data.data,
        jobsCodeValue: response.data.data.jobs_code
      });
    });
    Axios(options).then(response => {
      this.setState({
        payloadJobs: response.data.data
      });
    });
  }

  changeSignature = event => {
    console.log(
      "ChangeSignature",
      this.sigPad.getTrimmedCanvas().toDataURL("image/png")
    );
    this.setState({
      signature: this.sigPad.getTrimmedCanvas().toDataURL("image/png"),
      modalSignature: !this.state.modalSignature
    });
  };

  changejobs = event => {
    event.preventDefault();
    console.log("change jobs", this.state.jobs_code);
    this.setState({
      modalJobs: !this.state.modalJobs
    });
  };

  handleChange = event => {
    console.log("event", event.target.name);
    this.setState({
      [event.target.name]: event.target.value
    });
    if (event.target.name === "gender_code") {
      this.setState({
        gender: event.target.value
      });
    }
  };

  handleChangePic = event => {
    event.preventDefault();

    this.setState({
      modalPic: !this.state.modalPic
    });
    const user_id = this.props.match.params.user_id;
    const path = path_users + "/" + user_id + "/picture";
    var formData = new FormData();
    var imagefile = document.querySelector("#picture");
    formData.set("picture", imagefile.files[0]);
    const data = formData;
    axios(getData(path, "PUT", data))
      .then(response => {
        if (response.status === 200) {
          window.location.reload();
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === "ERROR") {
          this.setState({
            hidden: false,
            message: multiLanguage.alertInputNull
          });
        }
      });
  };

  handleClick = event => {
    event.preventDefault();
    this.setState({ fireRedirect: true });
    const user_id = this.props.match.params.user_id;
    const path = path_users + "/" + user_id;
    const data = {};
    data["username"] = this.state.username;
    data["email"] = this.state.email;
    data["first_name"] = formatCapitalize(this.state.first_name);
    data["last_name"] = formatCapitalize(this.state.last_name);
    data["contact"] = formatCapitalize(this.state.contact);
    data["gender_code"] = this.state.gender;
    data["address"] = formatCapitalize(this.state.address);
    data["role_code"] = this.state.role_code;
    data["signature"] = this.state.signature;
    data["jobs_code"] = this.state.jobs_code;
    data["nik"] = this.state.nik;
    data["npwp"] = this.state.npwp;
    axios(getData(path, method_put, data))
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ editData: true });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === "ERROR") {
          this.setState({
            hidden: false,
            message: multiLanguage.alertInputNull
          });
        }
      });
  };

  onSearch = value => {
    this.setState({
      fetching: true
    });
    const auth = Digest("/public" + path_jobs, "GET");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      url: `${baseUrl}/public${path_jobs}?limit=100&search=${value}`,
      data: null
    };
    Axios(options).then(response => {
      this.setState({
        payloadJobs: response.data.data,
        fetching: false
      });
    });
  };

  clear = () => {
    this.sigPad.clear();
  };

  handleBack = () => {
    this.setState({
      loading: true
    });
    window.history.back();
  };

  render() {
    const {
      payload,
      editData,
      modalPic,
      modalSignature,
      gender,
      hidden,
      message,
      payloadJobs
    } = this.state;

    const {
      username,
      email,
      first_name,
      last_name,
      contact,
      address,
      role_code,
      signature,
      jobs_name,
      nik
    } = payload;
    if (editData) {
      return <Redirect to={path_users} />;
    }

    const { user_id } = this.props.match.params;
    var role = "";

    switch (role_code) {
      case "DEV":
        role = "Developer";
        break;
      case "SUP":
        role = "Super User";
        break;
      case "ADM":
        role = "Administrator";
        break;
      case "ADT":
        role = "Admin TUK";
        break;
      case "MAG":
        role = "Management";
        break;
      case "ACS":
        role = multiLanguage.assessors;
        break;
      case "APL":
        role = multiLanguage.asesi;
        break;
      case "ANY":
        role = "Anonymous";
        break;

      default:
        break;
    }

    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading..">
        <div className="animated fadeIn">
          <div>
            <Modal
              isOpen={modalPic}
              toggle={this.toggle}
              className={this.props.className}
            >
              <ModalHeader toggle={this.toggle}>
                {multiLanguage.change} {multiLanguage.picture}
              </ModalHeader>
              <ModalBody>
                <Input
                  type="file"
                  id="picture"
                  name="picture"
                  onChange={this.handleChange}
                  required
                />
              </ModalBody>
              <ModalFooter>
                <Button color="danger" onClick={this.toggle}>
                  {multiLanguage.cancel}
                </Button>
                <Button
                  type="submit"
                  color="success"
                  onClick={this.handleChangePic}
                >
                  <i className="fa fa-check" /> {multiLanguage.submit}
                </Button>
              </ModalFooter>
            </Modal>
            <Modal
              isOpen={modalSignature}
              toggle={this.toggleSignature}
              className={this.props.className}
            >
              <ModalHeader toggle={this.toggleSignature}>
                {multiLanguage.change} {multiLanguage.signature}
              </ModalHeader>
              <ModalBody>
                <Row>
                  <Col>
                    <span>{multiLanguage.alertSignature}</span>
                  </Col>
                </Row>
                <Row>
                  <Col xs="12" md="9">
                    <div
                      style={{
                        backgroundColor: "gray",
                        width: 400,
                        height: 400,
                        marginBottom: "5%"
                      }}
                    >
                      <SignatureCanvas
                        ref={ref => {
                          this.sigPad = ref;
                        }}
                        penColor="black"
                        canvasProps={{
                          width: 400,
                          height: 400,
                          className: "sigCanvas"
                        }}
                      />
                    </div>
                    {this.sigPad === {} ? (
                      ""
                    ) : (
                      <div>
                        <Button color="warning" onClick={this.clear}>
                          <i className="fa fa-eraser" /> {multiLanguage.clear}
                        </Button>
                      </div>
                    )}
                  </Col>
                </Row>
                <p />
              </ModalBody>
              <ModalFooter>
                <Button color="danger" onClick={this.toggleSignature}>
                  {multiLanguage.cancel}
                </Button>
                <Button
                  type="submit"
                  color="success"
                  onClick={this.changeSignature}
                >
                  <i className="fa fa-check" /> {multiLanguage.submit}
                </Button>
              </ModalFooter>
            </Modal>
          </div>
          <Card>
            <CardHeader style={{ textAlign: "center" }}>
              {multiLanguage.Edit} Data
            </CardHeader>
            <CardBody>
              <AvForm
                action=""
                encType="multipart/form-data"
                className="form-horizontal"
              >
                <AvGroup row>
                  <Col md="3">
                    <img
                      className="profile-picture"
                      src={baseUrl + path_users + "/" + user_id + "/picture"}
                      alt=""
                    />
                    <p />
                    <Col md="24">
                      <Button
                        size="md"
                        color="success"
                        onClick={this.toggle}
                        className="change-pic"
                      >
                        {multiLanguage.change} {multiLanguage.picture}
                      </Button>
                    </Col>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="username">Username</Label>
                  </Col>
                  <Col xs="12" md="9">
                    <AvInput
                      type="text"
                      id="username"
                      name="username"
                      placeholder="username"
                      value={username}
                      readOnly
                    />
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="email">Email</Label>
                  </Col>
                  <Col xs="12" md="9">
                    <AvInput
                      type="email"
                      id="email"
                      name="email"
                      placeholder="Email"
                      value={email}
                      onChange={this.handleChange}
                      readOnly
                    />
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="first_name">
                      {multiLanguage.firstName}
                    </Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvInput
                      type="text"
                      style={{ borderColor: "black" }}
                      id="first_name"
                      name="first_name"
                      placeholder={multiLanguage.firstName}
                      value={first_name}
                      onChange={this.handleChange}
                    />
                  </Col>
                  <Col md="2">
                    <Label htmlFor="last_name">{multiLanguage.lastName}</Label>
                  </Col>
                  <Col xs="12" md="4">
                    <AvInput
                      type="text"
                      style={{ borderColor: "black" }}
                      id="last_name"
                      name="last_name"
                      placeholder={multiLanguage.lastName}
                      value={last_name === undefined ? "" : last_name}
                      onChange={this.handleChange}
                    />
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="contact">{multiLanguage.contact}</Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvField
                      type="text"
                      onChange={this.handleChange}
                      id="contact"
                      name="contact"
                      value={contact}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: multiLanguage.alertContact
                        },
                        pattern: {
                          value: "^[0-9]*$",
                          errorMessage: multiLanguage.alertPattertContact
                        },
                        minLength: {
                          value: 6,
                          errorMessage: multiLanguage.alertMinMaxContact
                        },
                        maxLength: {
                          value: 13,
                          errorMessage: multiLanguage.alertMinMaxContact
                        }
                      }}
                    />
                  </Col>
                  <Col md="2">
                    <Label>{multiLanguage.gender}</Label>
                  </Col>
                  <Col xs="12" md="4">
                    <RadioGroup
                      aria-label="Gender"
                      name="gender_code"
                      value={gender}
                      onChange={this.handleChange}
                    >
                      <FormControlLabel
                        value="M"
                        control={<Radio />}
                        label={multiLanguage.male}
                      />
                      <FormControlLabel
                        value="F"
                        control={<Radio />}
                        label={multiLanguage.female}
                      />
                    </RadioGroup>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="address">{multiLanguage.address}</Label>
                  </Col>
                  <Col xs="12" md="3">
                    <AvInput
                      type="text"
                      id="address"
                      name="address"
                      value={address}
                      onChange={this.handleChange}
                    />
                  </Col>
                  <Col md="2">
                    <Label>{multiLanguage.role}</Label>
                  </Col>
                  <Col md="4">
                    <AvInput
                      type="text"
                      id="role_code"
                      name="role_code"
                      placeholder={multiLanguage.role}
                      value={role}
                      readOnly
                    />
                    {/* {' '}
                    <Button onClick={this.toggleRole} size="md">
                      Ubah Role
                    </Button> */}
                  </Col>
                </AvGroup>
                {role_code === "APL" ? (
                  <AvGroup row>
                    <Col md="3">
                      <Label>{multiLanguage.jobs}</Label>
                    </Col>
                    <Col xs="12" md="3">
                      <AvField
                        type="select"
                        name="jobs_code"
                        onChange={this.handleChange}
                      >
                        <option>{jobs_name}</option>
                        {payloadJobs.map(({ jobs_code, jobs_name }) => {
                          return (
                            <option value={jobs_code} key={jobs_code}>
                              {jobs_name}
                            </option>
                          );
                        })}
                      </AvField>
                    </Col>
                    <Col md="2">
                      <Label for="NIK">NIK</Label>
                    </Col>
                    <Col xs="5" md="4">
                      <AvField
                        type="text"
                        id="nik"
                        name="nik"
                        maxlenght="16"
                        onChange={this.handleChange}
                        value={nik}
                        validate={{
                          required: {
                            value: true,
                            errorMessage: multiLanguage.alertName
                          }
                        }}
                      />
                    </Col>
                  </AvGroup>
                ) : (
                  ""
                )}
                <AvGroup row>
                  <Col md="3">
                    <Label for="signature">{multiLanguage.signature}</Label>
                  </Col>
                  <Col xs="5" md="4">
                    {signature === null ? (
                      <Button
                        onClick={this.toggleSignature}
                        className="btn-primary"
                      >
                        {multiLanguage.signature}
                      </Button>
                    ) : signature === "data:image/png;base64," ? (
                      <Button
                        onClick={this.toggleSignature}
                        className="btn-primary"
                      >
                        {multiLanguage.signature}
                      </Button>
                    ) : (
                      <h6>
                        Sudah Ada TTD,
                        <Button color="link" onClick={this.toggleSignature}>
                          Ganti?
                        </Button>
                      </h6>
                    )}
                  </Col>
                </AvGroup>
              </AvForm>
              <Alert className="text-center" color="danger" hidden={hidden}>
                {message}
              </Alert>
            </CardBody>
            <CardFooter>
              <Row>
                <Col md="1.5">
                  <Button
                    type="submit"
                    size="md"
                    color="danger"
                    onClick={this.handleBack}
                  >
                    <i className="fa fa-close" /> {multiLanguage.cancel}
                  </Button>
                  <p />
                </Col>
                <Col md="2">
                  <Button
                    type="submit"
                    size="md"
                    color="success"
                    onClick={this.handleClick}
                  >
                    <i className="fa fa-save" /> {multiLanguage.submit}
                  </Button>{" "}
                </Col>
              </Row>
            </CardFooter>
          </Card>
        </div>
      </LoadingOverlay>
    );
  }
}

export default EditData;
