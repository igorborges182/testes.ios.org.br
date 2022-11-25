<?php

function connect()
{
    try {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco" . $e->getMessage();
    }

    return $connection;
}

// TODO: Realizar a inserção do usuário aprovado no banco de dados com a tabela de envio em lote aprovado e usuário importado
function insert_entries_approved(array $data)
{
    global $wpdb;
    $table = $wpdb->prefix . 'approved_candidates';

    $exists = $wpdb->get_results(
                        "
                            SELECT * 
                            FROM $table
                            WHERE entry_id = ".$data['entry_id']
                    );

    if(!empty($exists)){
        return;
    }

    $wpdb->insert($table, $data);
}

function delete_entry_approved(int $id)
{
    global $wpdb;
    $table = $wpdb->prefix . 'approved_candidates';
    $wpdb->delete($table, ['entry_id' => $id]);
}

function get_entry_approved(int $id)
{
    global $wpdb;
    $table = $wpdb->prefix . 'approved_candidates';

    return $wpdb->get_results(
                        "
                            SELECT * 
                            FROM $table
                            WHERE entry_id = $id
                        "
                    );
}

function get_entrys_approved_and_unlocked_candidates(int $limit, int $offset)
{
    global $wpdb;
    $table = $wpdb->prefix . 'approved_candidates';

    $results['total'] = $wpdb->get_var(
                                        "
                                            SELECT COUNT(*) 
                                            FROM $table
                                            WHERE imported = 0
                                            AND is_approved_for_import = 1
                                        "
                                    );

    $results['results'] = $wpdb->get_results(
                                                "
                                                    SELECT * 
                                                    FROM $table
                                                    WHERE imported = 0
                                                    AND is_approved_for_import = 1
                                                    LIMIT $limit
                                                    OFFSET $offset
                                                "
                                            );

    return $results;
}

function update_entry_approved(array $data, int $id)
{
    global $wpdb;
    $table = $wpdb->prefix . 'approved_candidates';
    $wpdb->update($table, $data, array('entry_id' => $id));
}