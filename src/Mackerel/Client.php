<?php

namespace Mackerel;

use GuzzleHttp\Client as HttpClient;
use Mackerel\Objects\GraphAnnotation;
use Mackerel\Objects\Host;
use Mackerel\Objects\Monitor;

class Client
{
    const ERROR_MESSAGE_FOR_API_KEY_ABSENCE = "API key is absent.";

    /**
     * @var string
     */
    public $origin = 'https://mackerel.io';

    /**
     * @var string
     */
    public $apiKey = '';

    /**
     * @var int
     */
    public $timeout = 30;

    /**
     * @var int
     */
    public $openTimeout = 30;

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * Client constructor.
     * @param array $args
     * @throws \Exception
     */
    public function __construct(array $args = [])
    {
        if (array_key_exists('mackerel_origin', $args)) {
            $this->origin = $args['mackerel_origin'];
        }
        if (array_key_exists('mackerel_api_key', $args)) {
            $this->apiKey = $args['mackerel_api_key'];
        }
        if (array_key_exists('timeout', $args)) {
            $this->timeout = (int)$args['timeout'];
        }
        if (array_key_exists('open_timeout', $args)) {
            $this->openTimeout = (int)$args['open_timeout'];
        }

        if ($this->apiKey === '') {
            throw new \Exception(self::ERROR_MESSAGE_FOR_API_KEY_ABSENCE);
        }

        $this->client = new HttpClient([
            'base_uri' => $this->origin,
            'timeout' => $this->timeout,
            'connect_timeout' => $this->openTimeout,
            'headers' => [
                'X-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * @param Host $host
     * @return array|null
     * @throws \Exception
     */
    public function postHost(Host $host)
    {
        $path = '/api/v0/hosts';
        $response = $this->client->post($path, [
            'body' => json_encode($host),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $hostId
     * @return Host
     * @throws \Exception
     */
    public function getHost($hostId)
    {
        $path = '/api/v0/hosts/' . $hostId;
        $response = $this->client->get($path);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('GET %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return new Host($data['host']);
    }

    /**
     * @param $hostId
     * @param $status
     * @return array|null
     * @throws \Exception
     */
    public function updateHostStatus($hostId, $status)
    {
        if (!in_array($status, ['standby', 'working', 'maintenance', 'poweroff'])) {
            throw new \Exception('no such status: ', $status);
        }

        $path = sprintf('/api/v0/hosts/%s/status', $hostId);
        $response = $this->client->post($path, [
            'body' => json_encode([
                'status' => $status,
            ]),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $hostId
     * @param array $roleFullnames
     * @return mixed
     * @throws \Exception
     */
    public function updateHostRoleFullnames($hostId, array $roleFullnames)
    {
        $path = sprintf('/api/v0/hosts/%s/role-fullnames', $hostId);
        $response = $this->client->put($path, [
            'body' => json_encode([
                'roleFullnames' => $roleFullnames,
            ]),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $hostId
     * @return Host
     * @throws \Exception
     */
    public function retireHost($hostId)
    {
        $path = sprintf('/api/v0/hosts/%s/retire', $hostId);
        $response = $this->client->post($path, [
            'body' => json_encode([]),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $metrics
     * @return array|null
     * @throws \Exception
     */
    public function postMetrics(array $metrics)
    {
        $path = '/api/v0/tsdb';
        $response = $this->client->post($path, [
            'body' => json_encode($metrics),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $hostIds
     * @param array $names
     * @return array|null
     * @throws \Exception
     */
    public function getLatestMetrics(array $hostIds, array $names)
    {
        $query = implode('&',
            array_merge(
                array_map(function ($hostId) {
                    return sprintf('hostId=%s', $hostId);
                }, $hostIds),
                array_map(function ($name) {
                    return sprintf('name=%s', $name);
                }, $names)
            )
        );

        $path = '/api/v0/tsdb/latest?' . $query;
        $response = $this->client->get($path);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('GET %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true)['tsdbLatest'];
    }

    /**
     * @param string $serviceName
     * @param array $metrics
     * @return array|null
     * @throws \Exception
     */
    public function postServiceMetrics($serviceName, array $metrics)
    {
        $path = sprintf('/api/v0/services/%s/tsdb', $serviceName);
        $response = $this->client->post($path, [
            'body' => json_encode($metrics),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $graphDefs
     * @return array|null
     * @throws \Exception
     */
    public function defineGraphs(array $graphDefs)
    {
        $path = '/api/v0/graph-defs/create';
        $response = $this->client->post($path, [
            'body' => json_encode($graphDefs),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $opts
     * @return Host[]
     * @throws \Exception
     */
    public function getHosts(array $opts = [])
    {
        $keys = ['service', 'roles', 'name', 'status'];

        $params = array_filter($opts, function ($v, $k) {
            return array_key_exists($k, $keys);
        }, ARRAY_FILTER_USE_BOTH);

        $path = '/api/v0/hosts';
        $response = $this->client->get($path, $params);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('GET %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return array_map(function ($v) {
            return new Host($v);
        }, $data['hosts']);
    }

    /**
     * @param Monitor $monitor
     * @return Monitor
     * @throws \Exception
     */
    public function postMonitor(Monitor $monitor)
    {
        $path = '/api/v0/monitors';
        $response = $this->client->post($path, [
            'body' => $monitor->toJson(),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return new Monitor($data);
    }

    /**
     * @return Monitor[]
     * @throws \Exception
     */
    public function getMonitors()
    {
        $path = sprintf('/api/v0/monitors');
        $response = $this->client->get($path);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('GET %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return array_map(function (array $monitorJson) {
            return new Monitor($monitorJson);
        }, $data['monitors']);
    }

    /**
     * @param string $monitorId
     * @param Monitor $monitor
     * @return array|null
     * @throws \Exception
     */
    public function updateMonitor($monitorId, Monitor $monitor)
    {
        $path = '/api/v0/monitors/' . $monitorId;
        $response = $this->client->put($path, [
            'body' => $monitor->toJson(),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('PUT %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $monitorId
     * @return Monitor
     * @throws \Exception
     */
    public function deleteMonitor($monitorId)
    {
        $path = '/api/v0/monitors/' . $monitorId;
        $response = $this->client->delete($path);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('DELETE %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return new Monitor($data);
    }

    /**
     * @param GraphAnnotation $annotation
     * @return GraphAnnotation
     * @throws \Exception
     */
    public function postGraphAnnotation(GraphAnnotation $annotation)
    {
        $path = '/api/v0/graph-annotations';
        $response = $this->client->post($path, [
            'body' => $annotation->toJson(),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('POST %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return new GraphAnnotation($data);
    }

    /**
     * @param string $service
     * @param int $from
     * @param int $to
     * @return GraphAnnotation[]
     * @throws \Exception
     */
    public function getGraphAnnotations($service, $from, $to)
    {
        $path = '/api/v0/graph-annotations';
        $response = $this->client->get($path, [
            'query' => [
                'service' => $service,
                'from' => $from,
                'to' => $to,
            ],
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('GET %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return array_map(function (array $annotationJson) {
            return new GraphAnnotation($annotationJson);
        }, $data['graphAnnotations']);
    }

    /**
     * @param string $annotationId
     * @param GraphAnnotation $annotation
     * @return array|null
     * @throws \Exception
     */
    public function updateGraphAnnotation($annotationId, GraphAnnotation $annotation)
    {
        $path = '/api/v0/graph-annotations/' . $annotationId;
        $response = $this->client->put($path, [
            'body' => $annotation->toJson(),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('PUT %s failed: %d', $path, $response->getStatusCode()));
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $annotationId
     * @return GraphAnnotation
     * @throws \Exception
     */
    public function deleteGraphAnnotation($annotationId)
    {
        $path = '/api/v0/graph-annotations/' . $annotationId;
        $response = $this->client->delete($path);

        if ($response->getStatusCode() != 200) {
            throw new \Exception(sprintf('DELETE %s failed: %d', $path, $response->getStatusCode()));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        return new GraphAnnotation($data);
    }
}
