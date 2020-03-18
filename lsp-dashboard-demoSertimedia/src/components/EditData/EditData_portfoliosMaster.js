import React, { Component } from "react";
import { Card, CardHeader, CardBody, Row, Col } from "reactstrap";
import { multiLanguage } from "../Language/getBahasa";
import "../../css/loaderComponent.css";
import "antd/dist/antd.css";
import LabelRequired from "../Label/LabelRequired";
import EditDataPortofolioUmum from "./EditDataPortofolioUmum";
import EditDataPortofolioDasar from "./EditDataPortofolioDasar";
import Axios from "axios";
import { getData, path_masterData } from "../config/config";

export default class InputData_portofolio extends Component {
  constructor(props) {
    super(props);
    this.state = {
      types: 0,
      payload: [],
      document_state: []
    };
  }

  onChange = e => {
    this.setState({
      types: e.target.value
    });
  };

  componentDidMount() {
    const { master_portfolio_id } = this.props.match.params;
    Axios(getData(path_masterData + "/" + master_portfolio_id, "GET")).then(
      response => {
        this.setState({
          payload: response.data.data,
          document_state: response.data.data.document_state
        });
      }
    );
  }

  render() {
    const { types, payload, document_state } = this.state;
    const { master_portfolio_id } = this.props.match.params;
    return (
      <div>
        <Card>
          <CardHeader
            style={{ textAlign: "center" }}
          >{`${multiLanguage.add} Data`}</CardHeader>
          <CardBody>
            <Row>
              <Col md="2">
                <LabelRequired
                  fors="type"
                  label={multiLanguage.requirementsType}
                />
              </Col>
              <Col xs="5" md="4">
                {payload.type === "UMUM"
                  ? "Persyaratan Umum"
                  : "Persyaratan Dasar"}
              </Col>
            </Row>
            <p />
            {payload.type === "UMUM" ? (
              <EditDataPortofolioUmum
                type={types}
                payload={payload}
                master_portfolio_id={master_portfolio_id}
              />
            ) : (
              <EditDataPortofolioDasar
                type={types}
                payloadDetail={payload}
                master_portfolio_id={master_portfolio_id}
                document_state={document_state}
              />
            )}
          </CardBody>
        </Card>
      </div>
    );
  }
}
