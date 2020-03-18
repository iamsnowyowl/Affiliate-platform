import React, { Component } from "react";
import { Row, Col, Button, Alert, Label } from "reactstrap";
import { AvForm, AvGroup } from "availity-reactstrap-validation";
import { multiLanguage } from "../Language/getBahasa";
import LoadingOverlay from "react-loading-overlay";
import {
  baseUrl,
  path_schemaViews,
  getData,
  path_unitCompetention
} from "../config/config";
import Axios from "axios";
import { Digest } from "../../containers/Helpers/digest";
import FormMultiple from "../../views/Competences/UnitCompetention/FormMultiple";

class InputData_UnitCompetence extends Component {
  constructor(props) {
    super(props);
    this.state = {
      fetching: false,
      hidden: true,
      value: [],
      payload: [],
      loading: false,
      visibleForm: true,
      disableButton: true,
      unit_competence_id: "",
      unit_competence: [
        {
          unit_code: "",
          title: "",
          skkni: ""
        }
      ]
    };
  }

  componentDidMount() {
    const auth = Digest("/public" + path_schemaViews, "GET");
    var link = baseUrl + "/public" + path_schemaViews + "?limit=100";

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: link
    };
    Axios(options)
      .then(response => {
        this.setState({
          visibleForm: false,
          payload: response.data.data
        });
      })
      .catch(() => {
        console.log("error");
      });
  }

  handleChangeSubSchema = event => {
    this.setState({
      [event.target.name]: event.target.value,
      hidden: true
    });
  };

  remove = key => {
    var unitCompetensi = this.state.unit_competence;
    unitCompetensi.splice(key, 1);
    this.setState({
      unit_competence: unitCompetensi
    });
  };

  addUnit_competence = () => {
    this.setState(prevState => ({
      unit_competence: [
        ...prevState.unit_competence,
        { unit_code: "", title: "", skkni: "" }
      ]
    }));
  };

  back = event => {
    const codes = this.props;
    event.preventDefault();
    this.props.prevStep(codes);
  };

  handleChange_UnitCompetence = event => {
    if (
      ["unit_competence_id", "unit_code", "title", "skkni"].includes(
        event.target.className
      )
    ) {
      let unit_competence = [...this.state.unit_competence];
      unit_competence[event.target.dataset.id][event.target.className] =
        event.target.value;
      if (
        (unit_competence[0].unit_code &&
          unit_competence[0].title &&
          unit_competence[0].skkni) !== ""
      ) {
        this.setState({
          disableButton: false
        });
      } else {
        this.setState({
          disableButton: true
        });
      }
      this.setState({ unit_competence });
    } else {
      this.setState({
        [event.target.unit_code]: event.target.value
      });
    }
  };

  submit = (_event, errors, values) => {
    this.setState({
      errors,
      values,
      loading: true
    });
    const { sub_schema_number } = this.props.values;
    const { unit_competence } = this.state;
    console.log("sub schema number", sub_schema_number);
    console.log("unit competence", unit_competence);
    if (
      (unit_competence[0].skkni &&
        unit_competence[0].title &&
        unit_competence[0].unit_code) === ""
    ) {
      this.setState({
        hidden: false,
        messageError: multiLanguage.alertInput
      });
    } else {
      const data = {};
      // data['unit_code'] = this.state.unit_code;
      // data['title'] = this.state.title;
      // data['skkni'] = this.state.skkni;
      data["sub_schema_number"] = sub_schema_number;
      data["unit_competence"] = unit_competence;
      console.log("unit competence", data);

      Axios(getData(path_unitCompetention, "POST", data))
        .then(response => {
          console.log("response", response);
          window.location.assign("/schema/sub-schema");
        })
        .catch(() => {
          console.log("error");
        });
    }
  };

  render() {
    const {
      values: { sub_schema_name }
    } = this.props;
    const { unit_competence, hidden, messageError } = this.state;
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading...">
        <React.Fragment>
          <AvForm>
            <AvGroup row>
              <Col md="2">
                <Label>{multiLanguage.subSchemaName}</Label>
              </Col>
              <Col md="auto">{sub_schema_name}</Col>
            </AvGroup>
            <AvGroup onChange={this.handleChange_UnitCompetence}>
              <FormMultiple
                unit_competence={unit_competence}
                remove={this.remove}
              />
              <p />
              <Button onClick={this.addUnit_competence} color="primary">
                {multiLanguage.add} Data
              </Button>
            </AvGroup>
            {/* <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="unit_code">Unit Kode</Label>
                  </Col>
                  <Col>
                    <AvInput
                      type="text"
                      id="unit_code"
                      name="unit_code"
                      placeholder="Masukan Unit Kode"
                      onChange={this.handleChangeForm}
                    />
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="title">Judul Unit Kompetensi</Label>
                  </Col>
                  <Col>
                    <AvInput
                      type="text"
                      id="title"
                      name="title"
                      placeholder="Masukan Judul Unit"
                      onChange={this.handleChangeForm}
                    />
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="3">
                    <Label htmlFor="skkni">SKKNI</Label>
                  </Col>
                  <Col>
                    <AvInput
                      type="text"
                      id="skkni"
                      name="skkni"
                      placeholder="Masukan no SKKNI"
                      onChange={this.handleChangeForm}
                    />
                  </Col>
                </AvGroup> */}
          </AvForm>
          <Alert color="danger" hidden={hidden} className="text-centered">
            {messageError}
          </Alert>
          <Row>
            <Col md="1">
              <Button
                className="btn btn-success Btn-Submit"
                color="danger"
                size="md"
                onClick={this.back}
              >
                {multiLanguage.back}
              </Button>
            </Col>
            <Col md="1.5">
              <Button
                className="btn btn-success Btn-Submit"
                color="success"
                size="md"
                type="submit"
                onClick={this.submit}
              >
                <i className="fa fa-check" /> {multiLanguage.submit}
              </Button>
            </Col>
          </Row>
        </React.Fragment>
      </LoadingOverlay>
    );
  }
}

export default InputData_UnitCompetence;
