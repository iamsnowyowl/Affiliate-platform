import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Link } from "react-router-dom";
import { path_masterData, baseUrl } from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import "../../css/loaderDataTable.css";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import { Input, Icon, Table, Modal } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import ButtonDelete from "../../components/Button/ButtonDelete";

const Search = Input.Search;

class Portfolios extends Component {
  state = {
    data: [],
    pagination: {},
    loading: false,
    offset: 0,
    filteredInfo: null,
    searchText: ""
  };

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleSearch = searchText => {
    this.setState({ loading: true });
    const auth = Digest(path_masterData, "GET");
    reqwest({
      url: baseUrl + path_masterData + "?search=" + searchText,
      method: "GET",
      data: {
        limit: 100
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      console.log("response", response);
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      console.log("pagination", pagination);
      console.log("count", count);
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = {}) => {
    this.setState({ loading: true });
    const auth = Digest(path_masterData, "GET");
    reqwest({
      url: baseUrl + path_masterData,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = { ...this.state.pagination };
        const count = parseInt(response.count, 10);
        pagination.total = count;
        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })
      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        } else if (error.status === 419) {
          this.errorTrial();
        }
      });
  };

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
    this.get();
  }

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    let type = "DASAR,UMUM";
    let form_type = "checkbox,file,text,file_online";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }

    if (filters.type !== undefined) {
      switch (filters.type[0]) {
        case "DASAR":
          type = "DASAR";
          break;

        case "UMUM":
          type = "UMUM";
          break;

        default:
          break;
      }
    }

    if (filters.form_type !== undefined) {
      switch (filters.form_type[0]) {
        case "file":
          form_type = "file";
          break;

        case "text":
          form_type = "text";
          break;

        case "checkbox":
          form_type = "checkbox";
          break;

        case "file_online":
          form_type = "file_online";
          break;

        default:
          break;
      }
    }
    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting,
      type: type,
      form_type: form_type,
      // sortOrder: sorter.order,
      ...filters
    });
  };

  render() {
    const columns = [
      {
        key: "sub_schema_number",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.subSchemaCode}</h5>
        ),
        dataIndex: "sub_schema_number",
        sorter: true,
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "sub_schema_name",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.subSchemaName}</h5>
        ),
        dataIndex: "sub_schema_name",
        sorter: true,
        // width: "10%",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "type",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.type} {multiLanguage.portfolio}
          </h5>
        ),
        dataIndex: "type",
        // width: "5%",
        filters: [
          { text: "Dasar", value: "DASAR" },
          { text: "Umum", value: "UMUM" }
        ],
        filterMultiple: false,
        render: type => {
          if (type === "DASAR") {
            return <div>DASAR</div>;
          } else {
            return <div>UMUM</div>;
          }
        }
      },
      {
        key: "form_type",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.type} File</h5>
        ),
        dataIndex: "form_type",
        // width: "5%",
        filters: [
          { text: "File", value: "file" },
          { text: "Tetxt", value: "text" },
          { text: "Checkbox", value: "checkbox" },
          { text: "Online", value: "file_online" }
        ],
        filterMultiple: false,
        render: value => {
          return <div>{value === "file_online" ? "File Online" : value}</div>;
        }
      },
      {
        key: "form_name",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.name}</h5>,
        dataIndex: "form_name",
        sorter: true,
        // width: "5%",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "document_state",
        title: <h5 style={{ fontWeight: "bold" }}>State Dokumen</h5>,
        dataIndex: "document_state",
        // width: "5%",
        render: (value, row) => {
          var items = value.map((item, key) => {
            return (
              <ul key={key}>
                <li>
                  {item === "ON_REVIEW_APPLICANT_DOCUMENT"
                    ? multiLanguage.reviewDoc
                    : item === "ON_COMPLETED_REPORT"
                    ? multiLanguage.PraAssessmentCompleted
                    : item === "REAL_ASSESSMENT"
                    ? "Real Assessment"
                    : item === "PLENO_DOCUMENT_COMPLETED"
                    ? "Pleno"
                    : item === "PLENO_REPORT_READY"
                    ? multiLanguage.PlenoFinish
                    : item === "PRINT_CERTIFICATE"
                    ? multiLanguage.certificate
                    : item === "ADMIN_CONFIRM_FORM"
                    ? multiLanguage.stateReadyPraAssessment
                    : item}
                </li>
              </ul>
            );
          });
          return items;
        }
      },
      {
        key: "master_portfolio_id",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        dataIndex: "master_portfolio_id",
        // width: "6%",
        render: value => {
          if (value === "b5a1d6c3-a625-46e7-9ca4-543e5a8022d6") {
            return "";
          } else {
            return (
              <div>
                <a
                  href={"/portfolios/edit-portfolios/" + value}
                  className="btn btn-success col-md-auto"
                  title={multiLanguage.Edit}
                >
                  <i className="fa fa-edit" />
                </a>{" "}
                <ButtonDelete path={path_masterData} id_delete={value} />
              </div>
            );
          }
        }
      }
    ];
    return (
      <div className="animated fadeIn">
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
                  {multiLanguage.listPortfolio}
                </h5>
              </Col>
              <Col md="6" className="mb-3 mb-xl-0">
                <Link to={path_masterData + "/input"}>
                  <Button
                    className="float-md-right"
                    size="default"
                    color="primary"
                  >
                    <i className="fa fa-plus" />{" "}
                    {multiLanguage.inputRequirements}
                  </Button>
                </Link>
              </Col>
            </Row>
          </CardHeader>
          <CardBody>
            {`${multiLanguage.searching} `}
            <Search
              placeholder={multiLanguage.search}
              onSearch={this.handleSearch}
              onChange={this.handleChange}
              style={{ width: 310 }}
            />{" "}
            <p />
            <Table
              rowKey={record => record.master_portfolio_id}
              columns={columns}
              dataSource={this.state.data}
              pagination={this.state.pagination}
              loading={this.state.loading}
              onChange={this.handleTableChange}
              stripe
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default Portfolios;
