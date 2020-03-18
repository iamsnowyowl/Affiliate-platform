import React, { Component } from "react";
import { Row, Col, Label, Button } from "reactstrap";
import {
  AvForm,
  AvField,
  AvGroup,
  AvInput,
  AvFeedback,
  AvRadioGroup,
  AvRadio
} from "availity-reactstrap-validation";
import { multiLanguage } from "../Language/getBahasa";
import LabelRequired from "../Label/LabelRequired";
import { Select, Spin } from "antd";
import { path_jobs, baseUrl } from "../config/config";
import Axios from "axios";
import { Digest } from "../../containers/Helpers/digest";

const { Option } = Select;

class FormPersonalDetail extends Component {
  constructor(props) {
    super(props);
    this.state = {
      fetching: false,
      value: [],
      payload: []
    };
  }

  componentDidMount = () => {
    const auth = Digest("/public" + path_jobs, "GET");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      url: `${baseUrl}/public${path_jobs}?limit=100`,
      data: null
    };
    Axios(options).then(response => {
      this.setState({
        payload: response.data.data
      });
    });
  };

  continue = event => {
    event.preventDefault();
    this.props.nextStep();
  };

  back = event => {
    event.preventDefault();
    this.props.prevStep();
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
        payload: response.data.data,
        fetching: false
      });
    });
  };

  render() {
    const { values, handleChange, onChange } = this.props;
    const { fetching, payload } = this.state;
    return (
      <React.Fragment>
        <AvForm encType="multipart/form-data" className="form-horizintal">
          <AvGroup row>
            {" "}
            {/*First Name & Last Name*/}
            <Col md="2">
              <LabelRequired
                fors="first_name"
                label={multiLanguage.firstName}
              />
            </Col>
            <Col xs="12" md="3">
              <AvField
                type="text"
                id="first_name"
                name="first_name"
                defaultValue={values.first_name}
                onChange={handleChange("first_name")}
                validate={{
                  required: {
                    value: true,
                    errorMessage: multiLanguage.alertName
                  },
                  minLength: {
                    value: 3,
                    errorMessage: multiLanguage.minCharacter
                  }
                }}
              />
            </Col>
            <Col md="2" style={{ marginTop: "6px" }}>
              <Label for="last_name">{multiLanguage.lastName}</Label>
            </Col>
            <Col xs="5" md="3">
              <AvField
                type="text"
                id="last_name"
                defaultValue={values.last_name}
                name="last_name"
                onChange={handleChange("last_name")}
              />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <LabelRequired
                fors="place_of_birth"
                label={multiLanguage.placeBirth}
              />
            </Col>
            <Col xs="5" md="3">
              <AvInput
                type="text"
                id="place_of_birth"
                name="place_of_birth"
                onChange={handleChange("place_of_birth")}
                defaultValue={values.place_of_birth}
                validate={{
                  required: {
                    value: true,
                    errorMessage: multiLanguage.alertName
                  },
                  minLength: {
                    value: 3,
                    errorMessage: multiLanguage.minCharacter
                  }
                }}
              />
            </Col>
            <Col md="2">
              <LabelRequired
                fors="date_of_birth"
                label={multiLanguage.dateOfBirth}
              />
            </Col>
            <Col xs="5" md="3">
              <AvInput
                type="date"
                id="date_of_birth"
                name="date_of_birth"
                onChange={handleChange("date_of_birth")}
                defaultValue={values.date_of_birth}
                required
              />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <LabelRequired fors="contact" label={multiLanguage.contact} />
            </Col>
            <Col xs="5" md="3">
              <AvField
                type="number"
                id="contact"
                name="contact"
                onChange={handleChange("contact")}
                defaultValue={values.contact}
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
              <LabelRequired fors="gender_code" label={multiLanguage.gender} />
            </Col>
            <Col xs="5" md="4">
              <AvRadioGroup
                inline
                name="gender_code"
                required
                defaultValue={values.gender_code}
                errorMessage={multiLanguage.alertRadioButton}
              >
                <AvRadio
                  label={multiLanguage.female}
                  value="F"
                  onChange={handleChange("gender_code")}
                />
                <AvRadio
                  label={multiLanguage.male}
                  value="M"
                  onChange={handleChange("gender_code")}
                />
              </AvRadioGroup>
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <LabelRequired fors="address" label={multiLanguage.address} />
            </Col>
            <Col xs="12" md="9">
              <AvInput
                type="textarea"
                id="address"
                name="address"
                onChange={handleChange("address")}
                defaultValue={values.address}
              />
            </Col>
          </AvGroup>

          {values.role_code === "ACS" ? (
            <div>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired fors="NIK" label="NIK" />
                </Col>
                <Col xs="5" md="3">
                  <AvField
                    type="text"
                    id="nik"
                    name="nik"
                    maxlenght="16"
                    onChange={handleChange("nik")}
                    defaultValue={values.nik}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  />
                </Col>
                <Col md="2">
                  <LabelRequired
                    fors="registration_number"
                    label="No.Registrasi"
                  />
                </Col>
                <Col xs="5" md="4">
                  <AvInput
                    type="text"
                    id="registration_number"
                    name="registration_number"
                    maxlenght="16"
                    onChange={handleChange("registration_number")}
                    defaultValue={values.registration_number}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  />
                </Col>
              </AvGroup>
            </div>
          ) : values.role_code === "APL" ? (
            <div>
              <AvGroup row>
                <Col md="2">
                  <Label style={{ color: "#000000a6" }}>
                    {multiLanguage.jobs}
                  </Label>
                </Col>
                <Col xs="12" md="9">
                  <Select
                    showSearch
                    labelInValue
                    // value={value}
                    placeholder={
                      multiLanguage.select + " " + multiLanguage.jobs
                    }
                    notFoundContent={fetching ? <Spin size="small" /> : null}
                    filterOption={false}
                    onSearch={this.onSearch}
                    onChange={onChange("jobs")}
                    style={{ width: "100%" }}
                  >
                    {payload.map(d => (
                      <Option key={d.jobs_code}>{d.jobs_name}</Option>
                    ))}
                  </Select>
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired
                    fors="pendidikan_terakhir"
                    label={multiLanguage.education}
                  />
                </Col>
                <Col xs="5" md="3">
                  <AvField
                    type="select"
                    id="pendidikan_terakhir"
                    name="pendidikan_terakhir"
                    onChange={handleChange("pendidikan_terakhir")}
                    defaultValue={values.pendidikan_terakhir}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  >
                    <option value="">{multiLanguage.select}</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA/Sederajat">SMA/Sederajat</option>
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                  </AvField>
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired fors="NIK" label="NIK" />
                </Col>
                <Col xs="5" md="3">
                  <AvField
                    type="number"
                    name="nik"
                    onChange={handleChange("nik")}
                    defaultValue={values.nik}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  />
                </Col>
                <Col md="2">
                  <Label for="NPWP">NPWP</Label>
                </Col>
                <Col xs="5" md="3">
                  <AvInput
                    type="number"
                    id="npwp"
                    name="npwp"
                    onChange={handleChange("npwp")}
                    defaultValue={values.npwp}
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired fors="TUK" label="TUK" />
                </Col>
                <Col xs="5" md="3">
                  <AvField
                    type="select"
                    name="tuk_id"
                    onChange={handleChange("tuk_id")}
                    defaultValue={values.tuk_id}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  >
                    <option value="">{multiLanguage.select} TUK</option>
                    {values.payloadTuk.map(({ tuk_id, tuk_name }) => {
                      return (
                        <option value={tuk_id} key={tuk_id}>
                          {tuk_name}
                        </option>
                      );
                    })}
                  </AvField>
                </Col>
                <Col md="2">
                  <LabelRequired
                    fors="institution"
                    label={multiLanguage.institute}
                  />
                </Col>
                <Col xs="5" md="3">
                  <AvField
                    type="text"
                    style={{
                      textTransform: "uppercase"
                    }}
                    id="institution"
                    name="institution"
                    onChange={handleChange("institution")}
                    defaultValue={values.institution}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      }
                    }}
                  />
                </Col>
              </AvGroup>
            </div>
          ) : values.role_code === "ADT" ? (
            <div>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired fors="tuk_id" label={multiLanguage.nameTuk} />
                </Col>
                <Col xs="5" md="9">
                  <AvField
                    type="select"
                    name="tuk_id"
                    onChange={handleChange("tuk_id")}
                    defaultValue={values.tuk_id}
                    required
                  >
                    <option value="">{multiLanguage.select} TUK</option>
                    {values.payloadTuk.map(({ tuk_id, tuk_name }) => {
                      return (
                        <option value={tuk_id} key={tuk_id}>
                          {tuk_name}
                        </option>
                      );
                    })}
                  </AvField>
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
              </AvGroup>
            </div>
          ) : values.role_code === "MAG" ? (
            <div>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired fors="level" label={multiLanguage.position} />
                </Col>
                <Col xs="5" md="9">
                  <AvField
                    type="select"
                    name="level"
                    onChange={handleChange("level")}
                    defaultValue={values.level}
                    required
                  >
                    <option value="">
                      {multiLanguage.select} {multiLanguage.position} Management
                    </option>
                    <option value="1">Ketua LSP</option>
                    <option value="2">Wakil Ketua LSP</option>
                  </AvField>
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
              </AvGroup>
            </div>
          ) : (
            ""
          )}

          <Row>
            <Col md="6">
              <Button
                className="btn btn-success Btn-Submit"
                color="danger"
                size="md"
                type="submit"
                onClick={this.back}
              >
                {multiLanguage.back}
              </Button>
            </Col>
            <Col md="6">
              <Button
                className="btn btn-success Btn-Submit float-md-right"
                color="success"
                size="md"
                type="submit"
                onClick={this.continue}
                disabled={
                  (values.role_code === "ADT" && values.tuk_id === "") ||
                  (values.role_code === "APL" && values.tuk_id === "")
                    ? true
                    : false
                }
              >
                {multiLanguage.continue}
              </Button>
            </Col>
          </Row>
        </AvForm>
      </React.Fragment>
    );
  }
}

export default FormPersonalDetail;
