import React, { Component } from "react";
import Axios from "axios";

import FormUserDetails from "./FormUserDetail";
import FormPersonalDetail from "./FormPersonalDetail";
import { Steps, Modal } from "antd";

import Confirm from "./Confirm";
import { Digest } from "../../containers/Helpers/digest";
import { path_tuk, path_schemaViews, baseUrl } from "../config/config";
import { multiLanguage } from "../Language/getBahasa";

const { Step } = Steps;

const steps = [
  {
    title: "Form User"
  },
  {
    title: "Form Person"
  },
  {
    title: "Confirm"
  }
];

class UserForm extends Component {
  constructor(props) {
    super(props);
    this.state = {
      step: 1,
      current: 0,
      username: "",
      email: "",
      first_name: "",
      last_name: "",
      contact: "",
      gender_code: "",
      place_of_birth: "",
      date_of_birth: "",
      address: "",
      role_code: "",
      nik: "",
      npwp: "",
      picture: "",
      tuk_id: "",
      pendidikan_terakhir: "",
      institution: "",
      registration_number: "",
      signature: "",
      jobs: [],
      payloadJobs: [],
      payloadTuk: [],
      payloadSchema: [],
      hiddenAlert: true,
      messageAlert: ""
    };
  }

  // function GET data
  Get(options, response) {
    Axios(options)
      .then(res => {
        this.setState({
          [response]: res.data.data
        });
      })
      .catch(error => {
        if (error.response.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        } else if (error.response.status === 419) {
          this.errorTrial();
        }
      });
  }

  errorTrial = () => {
    Modal.error({
      title: "Pesan Error",
      content:
        "Masa trial anda telah habis,Harap menghubungi Admin NAS untuk info lebih lanjut",
      onOk() {
        localStorage.clear();
        window.location.replace("/login");
      }
    });
  };

  componentDidMount() {
    const authTuk = Digest(path_tuk, "GET");
    const authSchema = Digest("/public" + path_schemaViews, "GET");

    const optionsTuk = {
      method: authTuk.method,
      headers: {
        Authorization: authTuk.digest,
        "X-Lsp-Date": authTuk.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + path_tuk + "?limit=100",
      data: null
    };

    const optionsSchema = {
      method: authSchema.method,
      headers: {
        Authorization: authSchema.digest,
        "X-Lsp-Date": authSchema.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + "/public" + path_schemaViews + "?limit=100",
      data: null
    };

    this.Get(optionsTuk, "payloadTuk");
    this.Get(optionsSchema, "payloadSchema");
  }

  // procced to next step
  nextStep = () => {
    const {
      step,
      current,
      first_name,
      contact,
      gender_code,
      email,
      role_code,
      level,
      nik,
      npwp,
      place_of_birth
    } = this.state;
    this.setState({
      step: step + 1,
      current: current + 1
    });
    if (
      (first_name && contact && gender_code && email) === "" ||
      (role_code === "MAG" && level === undefined) ||
      (role_code === "ACS" && nik === "" && npwp === "") ||
      (role_code === "APL" && nik === "" && npwp === "")
    ) {
      this.setState({
        hiddenAlert: false,
        messageAlert: multiLanguage.alertInput
      });
    } else if (
      first_name.length < 3 ||
      place_of_birth.length < 3 ||
      contact.length < 6 ||
      (role_code === "ACS" && nik.length < 3 && npwp.length < 3) ||
      (role_code === "APL" && nik.length < 3 && npwp.length < 3)
    ) {
      this.setState({
        hiddenAlert: false,
        messageAlert: multiLanguage.alertErrorField
      });
    } else {
      this.setState({ hiddenAlert: true, messageAlert: "" });
    }
    console.log("kontak bos", this.state.hiddenAlert);
  };

  // Go back to prev step
  prevStep = () => {
    const { step, current } = this.state;
    this.setState({
      step: step - 1,
      current: current - 1
    });
  };

  // Handle fields change
  handleChange = input => event => {
    this.setState({ [input]: event.target.value });
  };

  onChange = input => value => {
    console.log("input", input);

    this.setState({
      jobs: value
    });
  };

  render() {
    const { step, current } = this.state;
    const {
      username,
      email,
      first_name,
      last_name,
      contact,
      gender_code,
      place_of_birth,
      date_of_birth,
      address,
      role_code,
      nik,
      npwp,
      picture,
      tuk_id,
      institution,
      registration_number,
      signature,
      payloadSchema,
      payloadTuk,
      level,
      hiddenAlert,
      messageAlert,
      jobs,
      pendidikan_terakhir
    } = this.state;
    const values = {
      username,
      email,
      first_name,
      last_name,
      contact,
      gender_code,
      place_of_birth,
      date_of_birth,
      address,
      role_code,
      nik,
      npwp,
      picture,
      tuk_id,
      institution,
      registration_number,
      signature,
      payloadSchema,
      payloadTuk,
      level,
      hiddenAlert,
      messageAlert,
      jobs,
      pendidikan_terakhir
    };
    return (
      <div>
        <Steps current={current}>
          {steps.map(item => (
            <Step key={item.title} title={item.title} />
          ))}
        </Steps>
        <p />
        <div className="steps-content" style={{ marginTop: "60px" }}>
          {step === 1 ? (
            <div>
              <FormUserDetails
                nextStep={this.nextStep}
                handleChange={this.handleChange}
                values={values}
              />
            </div>
          ) : step === 2 ? (
            <FormPersonalDetail
              nextStep={this.nextStep}
              prevStep={this.prevStep}
              handleChange={this.handleChange}
              values={values}
              onChange={this.onChange}
            />
          ) : step === 3 ? (
            <Confirm
              nextStep={this.nextStep}
              prevStep={this.prevStep}
              values={values}
            />
          ) : (
            ""
          )}
        </div>
      </div>
    );
  }
}

export default UserForm;
