import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Link } from "react-router-dom";
import { Input, Icon, Modal, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";

import {
  path_tuk,
  baseUrl,
  formatDate,
  getData,
  createPermission,
  updatePermission,
  path_tukAdd
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import ButtonDelete from "../../components/Button/ButtonDelete";
import ButtonEdit from "../../components/Button/ButtonEdit";

const Search = Input.Search;

class TUK extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      modal: false,
      payload: []
    };
  }

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
    const auth = Digest(path_tuk, "GET");
    reqwest({
      url: baseUrl + path_tuk + "?search=" + searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (params = {}) => {
    this.setState({ loading: true });
    const auth = Digest(path_tuk, "GET");
    reqwest({
      url: baseUrl + path_tuk,
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
    let tuk_type = "mandiri,sewaktu,sewaktu,APBD,tempat_kerja";
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

    if (filters.tuk_type !== undefined) {
      switch (filters.tuk_type[0]) {
        case "mandiri":
          tuk_type = "mandiri";
          break;

        case "sewaktu":
          tuk_type = "sewaktu";
          break;

        case "tempat_kerja":
          tuk_type = "Tempat Kerja";
          break;

        case "APBD":
          tuk_type = "APBD";
          break;

        default:
          break;
      }
    }
    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting,
      tuk_type: tuk_type
    });
  };

  info = row => {
    const path = path_tuk + "/" + row.tuk_id;
    Axios(getData(path, "GET"))
      .then(res => {
        if (res.data.responseStatus === "SUCCESS") {
          var payload = res.data.data;
          Modal.info({
            title: `Detail TUK ${payload.tuk_name}`,
            content: (
              <div>
                <Row>
                  <Col>{multiLanguage.number} SK</Col>
                  <Col>: {payload.number_sk}</Col>
                </Row>
                <Row>
                  <Col>{multiLanguage.tukName}</Col>
                  <Col>: {payload.tuk_name}</Col>
                </Row>
                <Row>
                  <Col>{multiLanguage.address}</Col>
                  <Col>
                    :
                    <a
                      href={`https://www.google.com/maps/search/?api=1&query=${payload.latitude},${payload.longitude}`}
                      target="_blank"
                    >
                      {payload.address}
                    </a>
                  </Col>
                </Row>
                <Row>
                  <Col>{multiLanguage.typeTuk}</Col>
                  <Col>: {payload.tuk_type}</Col>
                </Row>
                <Row>
                  <Col>{multiLanguage.expiredDate}</Col>
                  <Col>: {formatDate(payload.expired_date)}</Col>
                </Row>
                <Row>
                  <Col>{multiLanguage.description}</Col>
                  <Col>: {payload.description}</Col>
                </Row>{" "}
              </div>
            ),
            onOk() {}
          });
        }
      })
      .catch(error => {
        console.log("error");
        Modal.error({
          title: "Error",
          content:
            "Mohon maaf server sedang dalam perbaikan,mohon menghubungi administrator"
        });
      });
    const { payload } = this.state;
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

  render() {
    var item = "TUK";
    const { data, pagination, loading } = this.state;
    const columns = [
      {
        key: "tuk_name",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.name} TUK</h5>,
        dataIndex: "tuk_name",
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "address",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.address}</h5>,
        dataIndex: "address",
        width: "40%",
        sorter: true,
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "tuk_type",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.typeTuk}</h5>,
        dataIndex: "tuk_type",
        filters: [
          { text: "Mandiri", value: "mandiri" },
          { text: "Sewaktu", value: "sewaktu" },
          { text: "Tempat Kerja", value: "tempat_kerja" }
        ],
        filterMultiple: false,
        render: tuk_type => {
          if (tuk_type === "mandiri") {
            return <div style={{ textAlign: "center" }}>Mandiri</div>;
          } else if (tuk_type === "sewaktu") {
            return <div style={{ textAlign: "center" }}>Sewaktu</div>;
          } else if (tuk_type === "tempat_kerja") {
            return <div style={{ textAlign: "center" }}>Tempat Kerja</div>;
          } else {
            return <div style={{ textAlign: "center" }}>APBD</div>;
          }
        }
      },
      {
        key: "tuk_id",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        dataIndex: "tuk_id",
        render: (value, row) => {
          return updatePermission("TUK") ? (
            <div>
              <ButtonEdit url={"/tuk/edit-tuk/" + value} type="edit" />{" "}
              <ButtonDelete id_delete={value} path={path_tukAdd} />{" "}
              <Button color="primary" onClick={() => this.info(row)}>
                Detail
              </Button>
            </div>
          ) : (
            <div>
              <Button color="primary" onClick={() => this.info(row)}>
                detail
              </Button>
            </div>
          );
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
                  {multiLanguage.listTUK}
                </h5>
              </Col>
              {createPermission(item) === true ? (
                <Col md="6" className="mb-3 mb-xl-0">
                  <Link to={"/tuk/add-tuk"}>
                    <Button
                      className="float-md-right"
                      size="default"
                      color="primary"
                    >
                      <i className="fa fa-plus" /> {multiLanguage.add + " TUK"}
                    </Button>
                  </Link>
                </Col>
              ) : (
                ""
              )}
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
              rowKey={record => record.tuk_id}
              columns={columns}
              dataSource={data}
              pagination={pagination}
              loading={loading}
              onChange={this.handleTableChange}
              stripe
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default TUK;
